<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterAdminModel extends CI_Model
{


  public function ModelHelper($success, $error, $error_msg = '', $data_arr = array())
  {
    if ($success == true && $error == false) {
      $data = array(
        'success' => 'true',
        'error'   => 'false',
        'message' => $error_msg,
        'res_arr' => $data_arr
      );

      return $data;
    } elseif ($success == false && $error == true) {
      $data = array(
        'success' => 'false',
        'error'   => 'true',
        'message' => $error_msg
      );

      return $data;
    }
  }
  //Testing Function
  private function PrintArray($data)
  {
    print("<pre>" . print_r($data, true) . "</pre>");
    die;
  }
  //public function for logging in the business-admin to dashboard    
  public function MasterAdminLogin($data)
  {
    $this->db->select('*');
    $this->db->from('mss_master_admin');
    $this->db->where('master_admin_email', $data['master_admin_email']);
    $this->db->limit(1);

    $query = $this->db->get();

    if ($query->num_rows() == 1) {
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, 'No such business admin exists!');
    }
  }


  public function MasterAdminByEmail($email)
  {

    $this->db->select('*');
    $this->db->from('mss_master_admin');
    $this->db->where('master_admin_email', $email);
    $this->db->limit(1);

    //execute the query
    $query = $this->db->get();

    if ($query->num_rows() == 1) {
      return $this->ModelHelper(true, false, '', $query->row_array());
    } else {
      return $this->ModelHelper(false, true, "Duplicate emails are there!");
    }
  }


  //Generic function which will give all details by primary key of table
  public function DetailsById($id, $table_name, $where)
  {
    $this->db->select('*');
    $this->db->from($table_name);
    $this->db->where($where, $id);
    $this->db->limit(1);

    //execute the query
    $query = $this->db->get();

    if ($query->num_rows() == 1) {
      return $this->ModelHelper(true, false, '', $query->row_array());
    } else {
      return $this->ModelHelper(false, true, "Duplicate rows found!");
    }
  }

  //Generic function
  public function MultiWhereSelect($table_name, $where_array)
  {
    $this->db->select('*');
    $this->db->from($table_name);
    $this->db->where($where_array);

    //execute the query
    $query = $this->db->get();

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  //Generic function
  public function FullTable($table_name)
  {
    $query = $this->db->get($table_name);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  //Generic function
  public function Update($data, $table_name, $where)
  {
    $this->db->where($where, $data[$where]);
    $this->db->update($table_name, $data);
    if ($this->db->affected_rows() > 0) {
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, "No row updated!");
    }
  }


  //Generic function
  public function Insert($data, $table_name)
  {
	$data = $this->db->insert($table_name, $data);

    if ($data) {
       $data = array('insert_id' => $this->db->insert_id());
      return $this->ModelHelper(true, false, '', $data);
    } else {
      return $this->ModelHelper(false, true, "Check your inserted query!", $data);
    }
  }
  
  //Generic function
  public function InsertBatch($data, $table_name)
  {

    $data = $this->db->insert_batch($table_name, $data);
  
    if ($data) {
      // $data = array('insert_id' => $this->db->insert_id());
      return $this->ModelHelper(true, false, '', $data);
    } else {
      return $this->ModelHelper(false, true, "Check your inserted query!", $data);
    }
  }

  public function IsBeingUsed($table_name, $data, $where)
  {
    $this->db->select('*');
    $this->db->from($table_name);
    $this->db->where($where, $data);

    $query = $this->db->get();

    if ($query->num_rows() >= 1) {
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, 'No Row currently in use!');
    }
  }

  public function MasterAdminPackages($master_id)
  {

    $sql = "SELECT A.business_admin_package_id,A.master_id,B.package_id,B.package_name,A.package_expiry_date FROM mss_business_admin_packages_new AS A,mss_packages AS B WHERE A.master_id = " . $this->db->escape($master_id) . " AND A.package_id = B.package_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function SubCategories($where)
  {
    $sql = "SELECT sub_category_id,sub_category_category_id,sub_category_name,sub_category_is_active,sub_category_description,category_name FROM mss_sub_categories AS A,mss_categories AS B WHERE A.sub_category_category_id = B.category_id AND B.category_business_admin_id = " . $this->db->escape($where['category_business_admin_id']) . " AND sub_category_is_active = " . $this->db->escape($where['sub_category_is_active']) . " AND B.category_business_outlet_id=" . $this->db->escape($where['category_business_outlet_id']) . "";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  
   public function MasterSubCategories($data,$getSubCategoryById=0)
  {
	
    $sql = "SELECT sub_category_id,sub_category_category_id,sub_category_name,sub_category_is_active,sub_category_description,category_name,category_type FROM master_sub_categories AS A,master_categories AS B WHERE A.sub_category_category_id = B.category_id AND B.master_id = " . $this->db->escape($data['master_id']) . " AND A.sub_category_is_active = " . $this->db->escape($data['sub_category_is_active']) . " ";
    
	if($getSubCategoryById>0){
		$sql .= "  AND A.sub_category_id = " . $this->db->escape($getSubCategoryById) . " ";
	
	}
	$query = $this->db->query($sql);
	
    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  
  public function Services($where)
  {
    $sql = "SELECT * FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND A.category_business_admin_id = " . $this->db->escape($where['category_business_admin_id']) . " AND C.service_is_active = " . $this->db->escape($where['service_is_active']) . " AND A.category_business_outlet_id = " . $this->db->escape($where['category_business_outlet_id']) . " AND C.service_type = " . $this->db->escape($where['service_type']) . "";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
 
   public function MasterServices($where,$service_id=0)
  {
    $sql = "SELECT * FROM master_categories AS A,master_sub_categories AS B,master_services AS C WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND A.master_id = " . $this->db->escape($where['master_id']) . " AND C.service_is_active = " . $this->db->escape($where['service_is_active']) . "  AND C.service_type = " . $this->db->escape($where['service_type']) . "";
    if($service_id>0){
		$sql .= " AND C.service_id = ".$service_id."";
	}
    $query = $this->db->query($sql);
    
    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
 

  public function DeactiveCategory($category_id)
  {
    $sql = "UPDATE mss_services,mss_sub_categories,mss_categories SET mss_services.service_is_active = FALSE,mss_sub_categories.sub_category_is_active = FALSE,mss_categories.category_is_active = FALSE WHERE 
				mss_sub_categories.sub_category_id = mss_services.service_sub_category_id AND mss_categories.category_id =" . $this->db->escape($category_id) . "";

    $query = $this->db->query($sql);

    if ($this->db->affected_rows() > 0) {
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, "No row updated!");
    }
  }

 public function DeactiveMasterCategory($category_id){
   	  
    /*$sql = "UPDATE master_services,master_sub_categories,master_categories SET master_services.service_is_active = FALSE,master_sub_categories.sub_category_is_active = FALSE,master_categories.category_is_active = FALSE WHERE 
				master_sub_categories.sub_category_id = master_services.service_sub_category_id AND master_sub_categories.sub_category_category_id = master_categories.category_id  AND master_categories.category_id =" . $this->db->escape($category_id) . "";
 
    */
	
	$sql = "UPDATE master_categories SET category_is_active = FALSE WHERE category_id =" . $this->db->escape($category_id) . "";
	$query = $this->db->query($sql);
    
    if ($this->db->affected_rows() > 0) {
		
	  // Deactivated all which belong to this category like Sub category/Service/  	
	  $sql = "UPDATE master_sub_categories SET sub_category_is_active = FALSE WHERE sub_category_category_id =" . $this->db->escape($category_id) . "";
	  $this->db->query($sql);
	  
	 
	  $sql = "UPDATE master_services,master_sub_categories SET master_services.service_is_active = FALSE WHERE 
				master_sub_categories.sub_category_id = master_services.service_sub_category_id AND master_sub_categories.sub_category_category_id =" . $this->db->escape($category_id) . "";
	  $this->db->query($sql);	
	 	  
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, "No row updated!");
    }
  }  
  
  public function DeactiveSubCategory($sub_category_id)
  {
    $sql = "UPDATE mss_sub_categories,mss_services SET mss_sub_categories.sub_category_is_active = FALSE,mss_services.service_is_active = FALSE WHERE mss_sub_categories.sub_category_id = mss_services.service_sub_category_id AND mss_sub_categories.sub_category_id = " . $this->db->escape($sub_category_id) . "";

    $query = $this->db->query($sql);

    if ($this->db->affected_rows() > 0) {
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, "No row updated!");
    }
  }
 
  public function MasterDeactiveSubCategory($sub_category_id)
  {
    //$sql = "UPDATE master_sub_categories,master_services SET master_sub_categories.sub_category_is_active = FALSE,master_services.service_is_active = FALSE WHERE master_sub_categories.sub_category_id = master_services.service_sub_category_id AND master_sub_categories.sub_category_id = " . $this->db->escape($sub_category_id) . "";
    
	$sql = "UPDATE master_sub_categories SET sub_category_is_active = FALSE WHERE sub_category_id =" . $this->db->escape($sub_category_id) . "";
	
    $query = $this->db->query($sql);

    if ($this->db->affected_rows() > 0) {
	  // Deactivated all which belong to this sub category like Sub category/Service/  	
	  $sql = "UPDATE master_services SET service_is_active = FALSE WHERE service_sub_category_id =" . $this->db->escape($sub_category_id) . "";
	  $this->db->query($sql);	
		
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, "No row updated!");
    }
  }
  
  public function ViewCompositionBasic($where)
  {
    $sql = "SELECT * FROM mss_raw_composition,mss_services,mss_sub_categories,mss_categories WHERE mss_raw_composition.service_id = mss_services.service_id AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id AND mss_sub_categories.sub_category_category_id = mss_categories.category_id AND mss_categories.category_business_admin_id = " . $this->db->escape($where['business_admin_id']) . " AND mss_categories.category_business_outlet_id =" . $this->db->escape($where['business_outlet_id']) . " GROUP BY mss_raw_composition.service_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function ViewComposition($where)
  {
    $sql = "SELECT * FROM mss_raw_composition,mss_raw_material_categories,mss_services WHERE mss_raw_composition.rmc_id = mss_raw_material_categories.raw_material_category_id AND mss_raw_material_categories.raw_material_business_admin_id = " . $this->db->escape($where['business_admin_id']) . " AND mss_raw_material_categories.raw_material_business_outlet_id = " . $this->db->escape($where['business_outlet_id']) . " AND mss_raw_composition.service_id = " . $this->db->escape($where['service_id']) . "  AND mss_raw_composition.service_id = mss_services.service_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function DeleteMultiple($table_name, $data, $where)
  {
    $this->db->where($where, $data);
    $this->db->delete($table_name);

    if (!$this->db->affected_rows()) {
      $result = 'Error! ID [' . $data . '] not found';
      return $this->ModelHelper(false, true, $result);
    } else {
      return $this->ModelHelper(true, false);
    }
  }

  public function DeleteRawMaterialCategory($data)
  {

    $this->db->trans_start();

    $this->db->where('rmc_id', $data['raw_material_category_id']);
    $this->db->delete('mss_raw_material_stock');

    $this->db->where('raw_material_category_id', $data['raw_material_category_id']);
    $this->db->update('mss_raw_material_categories', $data);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      return $this->ModelHelper(false, true, 'Cannot be processed!');
    }

    return $this->ModelHelper(true, false);
  }
  
   public function AssignPackageToMultipleOutlet($data,$outletIds){
		$records = array();
		if(!empty($outletIds)){
			foreach($outletIds as $key=>$outlet_id){
				$records[] = array('package_id'=>$data['package_id'],'outlet_id'=>$outlet_id,'master_id'=>$data['master_id']);
			}
			
			$this->InsertBatch($records,'mss_package_outlet_association');
		}
	}
  
   // Add category packages for salon
    public function AddServiceCategoryBulkPackage($data,$categories,$count){   

      $result = $this->Insert($data,'mss_salon_packages_master_new');
      $last_insert_id = $result['res_arr']['insert_id'];

      //create a services packages
      for($i=0;$i < count($categories);$i++){
					$sub_categories=$this->MultiWhereSelect('master_sub_categories',array('sub_category_category_id' => $categories[$i]));
					$sub_categories=$sub_categories['res_arr'];
					for($j=0;$j< count($sub_categories);$j++){
          //for each sub category id -> add all services in it
						$services_data = $this->MultiWhereSelect('master_services',array('service_sub_category_id' => $sub_categories[$i]['sub_category_id']));
						
						$services = $services_data['res_arr'];
						foreach ($services as $service) {
								$data_2 = array(
										'salon_package_id' => $last_insert_id,
										'service_id' 		=> $service['service_id'],
										'discount_percentage' => 100,
										'service_count' => (int)$count[$j]
								);
								$result_2 = $this->Insert($data_2,'mss_salon_package_data');
						}
					}
      }

      return $this->ModelHelper(true,false);
    }
	
	public function AddDiscountCategoryBulkPackage($data,$categories,$cat_price,$discounts,$count){

        $result = $this->Insert($data,'mss_salon_packages_master_new');

        $last_insert_id = $result['res_arr']['insert_id'];

        //create a discounts packages
        for($i=0;$i<count($categories);$i++){
					$sub_categories=$this->MultiWhereSelect('master_sub_categories',array('sub_category_category_id' => $categories[$i]));
					$sub_categories=$sub_categories['res_arr'];
					for($j=0;$j< count($sub_categories);$j++){
					//for each sub category id -> add all services in it
						$services_data = $this->MultiWhereSelect('master_services',array('service_sub_category_id' => $sub_categories[$j]['sub_category_id']));
						$services = $services_data['res_arr'];
						
						for($k=0;$k< count($services);$k++) {
							if($services[$k]['service_price_inr'] > $cat_price[$i] ){
								$data_2 = array(
									'salon_package_id' => $last_insert_id,
									'service_id' => $services[$k]['service_id'],
									'discount_percentage' => (int)$discounts[$i],
									'service_count' => $count[$i]
								);
								$result_2 = $this->Insert($data_2,'mss_salon_package_data');
							}
						}
					}
        }
        
        return $this->ModelHelper(true,false);
    }
  
 //ServicePAckage
		public function AddDiscountServicePackage($post,$data,$count,$where){
        // $this->PrintArray($_POST['category_type1']);
		$result = $this->Insert($data,'mss_salon_packages_master_new');
		$last_insert_id = $result['res_arr']['insert_id'];	
		
        if(!empty($_POST['category_type1'])){
            for($i=0;$i<count($_POST['category_type1']);$i++){
                $filter=array(
                    'category_type'=>$_POST['category_type1'][$i],
                    'min_price'=>$_POST['min_price1'][$i],
                    'max_price'=>$_POST['max_price1'][$i],
                    'business_admin_id'=>$where['business_admin_id']
                    //'business_outlet_id'=>$where['business_outlet_id']
                );
                // $categories=$this->ServiceByPrice($co);
                $result_2=$this->ServiceBetweenPrice($filter);
                $result_2=$result_2['res_arr'];
                // echo $result_2[1]['service_id'];
                // exit;
                for($k=0;$k< count($result_2);$k++){
                    $data_2 = array(
                    'salon_package_id' => $last_insert_id,
                    'service_id' => $result_2[$k]['service_id'],
                    'discount_percentage' => $_POST['special_discount1'][$i],
                    'service_monthly_discount'=>$_POST['package_monthly_discount'],
                    'birthday_discount' =>$_POST['birthday_discount'],
                    'anni_discount'	=> $_POST['anniversary_discount'],
                    'service_count' => $count
                    );							
                    $result = $this->Insert($data_2,'mss_salon_package_data');            
                }
            }
        }        
        if(!empty($_POST['special_category_id2'])){
            for($i=0;$i<count($_POST['special_category_id2']);$i++){
                $filter2=array(
                    // 'category_type'=>$_POST['category_type2'],
                    'category_id'=>$_POST['special_category_id2'][$i],
                    'min_price'=>$_POST['min_price2'][$i],
                    'max_price'=>$_POST['max_price2'][$i],
                    'business_admin_id'=>$where['business_admin_id'],
                    'business_outlet_id'=>$where['business_outlet_id']
                );
                // $categories=$this->ServiceByPrice($co);
                $result_3=$this->ServiceBetweenPrice2($filter2);
                $result_3=$result_3['res_arr'];
                for($k=0;$k< count($result_3);$k++){
                    $data_3 = array(
                    'salon_package_id' => $last_insert_id,
                    'service_id' => $result_3[$k]['service_id'],
                    'discount_percentage' => $_POST['special_discount2'][$i],
                    'service_monthly_discount'=>$_POST['package_monthly_discount'],
                    'birthday_discount' =>$_POST['birthday_discount'],
                    'anni_discount'	=> $_POST['anniversary_discount'],
                    'service_count' => $count
                    );							
                    $result_2 = $this->Insert($data_3,'mss_salon_package_data');            
                }
            }
        }
        if(!empty($_POST['special_sub_category_id3'])){
            for($i=0;$i< count($_POST['special_sub_category_id3']);$i++){
                $filter3=array(
                    // 'category_type'=>$_POST['category_type3'],
                    'sub_category_id'=>$_POST['special_sub_category_id3'][$i],
                    'min_price'=>$_POST['min_price3'][$i],
                    'max_price'=>$_POST['max_price3'][$i],
                    'business_admin_id'=>$where['business_admin_id'],
                    'business_outlet_id'=>$where['business_outlet_id']
                );
                // $categories=$this->ServiceByPrice($co);
                $result_3=$this->ServiceBetweenPrice3($filter3);
                $result_3=$result_3['res_arr'];
                for($k=0;$k< count($result_3);$k++){
                    $data_3 = array(
                    'salon_package_id' => $last_insert_id,
                    'service_id' => $result_3[$k]['service_id'],
                    'discount_percentage' => $_POST['special_discount3'][$i],
                    'service_monthly_discount'=>$_POST['package_monthly_discount'],
                    'birthday_discount' =>$_POST['birthday_discount'],
                    'anni_discount'	=> $_POST['anniversary_discount'],
                    'service_count' => $count
                    );							
                    $result_2 = $this->Insert($data_3,'mss_salon_package_data');            
                }
            }
        }
        if(!empty($_POST['special_service_id4'])){
            for($i=0;$i< count($_POST['special_service_id4']);$i++){	
                $data_3 = array(
                    'salon_package_id' => $last_insert_id,
                    'service_id' => $_POST['special_service_id4'][$i],
                    'discount_percentage' => $_POST['special_discount4'][$i],
                    'service_monthly_discount'=>$_POST['package_monthly_discount'],
                    'birthday_discount' =>$_POST['birthday_discount'],
                    'anni_discount'	=> $_POST['anniversary_discount'],
                    'service_count' => $count
                );							
                $result_2 = $this->Insert($data_3,'mss_salon_package_data');                 
            }
        }
		// $this->PrintArray($temp);
		// exit;
        return $this->ModelHelper(true,false);
    }
  
  public function AddServiceSubCategoryBulkPackage($data, $sub_categories, $count)
  {

    $result = $this->Insert($data, 'mss_salon_packages_master_new');

    $last_insert_id = $result['res_arr']['insert_id'];

    //create a services packages
    for ($i = 0; $i < count($sub_categories); $i++) {

      //for each sub category id -> add all services in it
      $services_data = $this->MultiWhereSelect('master_services', array('service_sub_category_id' => $sub_categories[$i]));

      $services = $services_data['res_arr'];
      foreach ($services as $service) {
        $data_2 = array(
          'salon_package_id' => $last_insert_id,
          'service_id' => $service['service_id'],
          'discount_percentage' => 100,
          'service_count' => (int) $count[$i]
        );
        $result_2 = $this->Insert($data_2, 'mss_salon_package_data');
      }
    }

    return $this->ModelHelper(true, false);
  }

  public function AddDiscountSubCategoryBulkPackage($data, $sub_categories, $discounts, $count)
  {
    $result = $this->Insert($data, 'mss_salon_packages_master_new');

    $last_insert_id = $result['res_arr']['insert_id'];

    //create a discounts packages
    for ($i = 0; $i < count($sub_categories); $i++) {

      //for each sub category id -> add all services in it
      $services_data = $this->MultiWhereSelect('master_services', array('service_sub_category_id' => (int) $sub_categories[$i]));

      $services = $services_data['res_arr'];
      foreach ($services as $service) {
        $data_2 = array(
          'salon_package_id' => $last_insert_id,
          'service_id' => $service['service_id'],
          'discount_percentage' => (int) $discounts[$i],
          'service_count' => (int) $count[$i]
        );
        $result_2 = $this->Insert($data_2, 'mss_salon_package_data');
      }
    }

    return $this->ModelHelper(true, false);
  }

  public function AddServicePackageForSalon($data, $services, $count_service)
  {

    $result = $this->Insert($data, 'mss_salon_packages_master_new');
	$last_insert_id = $result['res_arr']['insert_id'];
   
    $count = 0;

    //create a services packages
    for ($i = 0; $i < count($services); $i++) {
      $data_2 = array(
        'salon_package_id' => $last_insert_id,
        'service_id' => (int) $services[$i],
        'discount_percentage' => 100,
        'service_count' => (int) $count_service[$i]
      );
      $result_2 = $this->Insert($data_2, 'mss_salon_package_data');

      if ($result_2['success'] == 'true') {
        $count = $count + 1;
      }
    }
    if ($count  == count($services)) {
      return $this->ModelHelper(true, false);
    } else {
      return $this->ModelHelper(false, true, "Cannot Process!");
    }
  }

  public function AddDiscountPackageForSalon($data, $services, $discounts, $count_discount)
  {
    $result = $this->Insert($data, 'mss_salon_packages_master_new');

    $last_insert_id = $result['res_arr']['insert_id'];
	

    $count = 0;
    //create a discounts packages
    for ($i = 0; $i < count($services); $i++) {

      $data_2 = array(
        'salon_package_id' => $last_insert_id,
        'service_id' => (int) $services[$i],
        'discount_percentage' => (int) $discounts[$i],
        'service_count' => (int) $count_discount[$i]
      );
      $result_2 = $this->Insert($data_2, 'mss_salon_package_data');

      if ($result_2['success'] == 'true') {
        $count = $count + 1;
      }
    }


    if ($count  == count($services)) {
      return $this->ModelHelper(true, false);
    } elseif ($count == 0) {
      return $this->ModelHelper(false, true, "Cannot Process!");
    } else {
      return $this->ModelHelper(true, false, "Parially Processed,Consider deactivating package!");
    }
  }

  private function CheckIfPackageServiceExists($insert_id, $service_id)
  {
    $this->db->select('*');
    $this->db->from('mss_salon_package_data');
    $this->db->where('salon_package_id', $insert_id);
    $this->db->where('service_id', $service_id);

    $query = $this->db->get();

    if ($query->num_rows() === 1) {
      return $this->ModelHelper(true, false);
    } else if ($query->num_rows() === 0) {
      return $this->ModelHelper(false, true);
    }
  }

  public function GetAllPackages($where,$filter=array())
  {
    //$sql = "SELECT * FROM mss_salon_packages_master_new WHERE master_id = " . $this->db->escape($where['master_id']) . " AND business_outlet_id = " . $this->db->escape($where['business_outlet_id']) . "";
    $sql = "SELECT * FROM mss_salon_packages_master_new WHERE salon_package_id IN (SELECT package_id FROM `mss_package_outlet_association` WHERE `outlet_id`=" . $this->db->escape($where['business_outlet_id']) . " AND `master_id`=" . $this->db->escape($where['master_id']) . ")";
	if(!empty($filter)){
			if(isset($filter['searchValue']) && $filter['searchValue']!=""){
			 $sql .= " AND  (salon_package_name like '%".$filter['searchValue']."%' or salon_package_type like '%".$filter['searchValue']."%' )";
			}
			
			$sql .= "  order by ".$filter['columnName']." ".$filter['columnSortOrder']." limit ".$filter['row'].",".$filter['rowperpage'];
	}
	
	$query = $this->db->query($sql);
	
    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  
  public function GetAllPackagesCountWithFilter($where,$filter=array())
  {
    //$sql = "SELECT * FROM mss_salon_packages_master_new WHERE master_id = " . $this->db->escape($where['master_id']) . " AND business_outlet_id = " . $this->db->escape($where['business_outlet_id']) . "";
    $sql = "SELECT * FROM mss_salon_packages_master_new WHERE salon_package_id IN (SELECT package_id FROM `mss_package_outlet_association` WHERE `outlet_id`=" . $this->db->escape($where['business_outlet_id']) . " AND `master_id`=" . $this->db->escape($where['master_id']) . ")";
	if(!empty($filter)){
			if(isset($filter['searchValue']) && $filter['searchValue']!=""){
			 $sql .= " AND  (salon_package_name like '%".$filter['searchValue']."%' or salon_package_type like '%".$filter['searchValue']."%' )";
			}
	}
	
	$query = $this->db->query($sql);
    if ($query) {
      return $this->ModelHelper(true, false, '', $query->num_rows());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  
   public function GetAllPackagesCount($where){
        $sql = "SELECT * FROM mss_salon_packages_master_new WHERE salon_package_id IN (SELECT package_id FROM `mss_package_outlet_association` WHERE `outlet_id`=" . $this->db->escape($where['business_outlet_id']) . " AND `master_id`=" . $this->db->escape($where['master_id']) . ")";
		$query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->num_rows());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

 public function GenerateReports($data)
  {
    if ($data['report_name'] == 'BWSR') {
      return $this->GetBillWiseSalesReport($data);
    } elseif ($data['report_name'] == 'DWCSR') {
      return $this->GetDateWiseCategorySalesReport($data);
    } elseif ($data['report_name'] == 'SCWSR') {
      return $this->GetSubCategoryWiseSalesReport($data);
    } elseif ($data['report_name'] == 'PWSR') {
      return $this->GetPackageWiseSalesReport($data);
    } elseif ($data['report_name'] == 'IWSR') {
      return $this->GetItemWiseSalesReport($data);
    } elseif ($data['report_name'] == 'IWCR') {
      return $this->GetItemWiseCustomerReport($data);
    } elseif ($data['report_name'] == 'SROTC') {
      return $this->GetStockReportOTC($data);
    } elseif ($data['report_name'] == 'SRRM') {
      return $this->GetStockReportRawMaterial($data);
    } elseif ($data['report_name'] == 'BWDR') {
      return $this->GetBillWiseDiscountReport($data);
    } elseif ($data['report_name'] == 'EWPR') {
      return $this->GetEmployeeWisePerformanceReport($data);
    } elseif ($data['report_name'] == 'PR') {
      return $this->GetPackageReport($data);
    } elseif ($data['report_name'] == 'PWR') {
      return $this->GetPaymentWiseReport($data);
    } elseif ($data['report_name'] == 'VWR') {
      return $this->GetVirtualWalletReport($data);
    } elseif ($data['report_name'] == 'PAR') {
      return $this->GetPendingAmountReport($data);
    } elseif ($data['report_name'] == 'PATR') {
      return $this->GetPendingAmountTransactionReport($data);
    } elseif ($data['report_name'] == 'AR') {
      return $this->GetAppointmentReport($data);
    } else {
      $this->ModelHelper(false, true, "No such report exists!");
    }
  }

private function GetPackageReport($data)
  {
    $sql = "SELECT
                    mss_package_transactions.package_txn_id AS 'Bill No',
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    date(mss_package_transactions.datetime) AS 'Purchase Date',
                    mss_salon_packages.salon_package_name AS 'Package Name',
                    mss_salon_packages.salon_package_type AS 'Package Type',
                    mss_package_transactions.package_txn_value AS 'Bill Amount',
                    mss_package_transactions.package_txn_discount AS 'Discount Given',
                    mss_package_transaction_settlements.settlement_way AS 'Settlement Way',
                    mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
                    date_add(date(now()),INTERVAL mss_salon_packages.salon_package_validity MONTH) AS 'Expiry Date',
                    mss_employees.employee_first_name AS 'Expert Name',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                    mss_package_transactions,
                    mss_customers,
                    mss_salon_packages,
                    mss_transaction_package_details,
                    mss_employees,
                    mss_package_transaction_settlements,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
                    AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_txn_id
                    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                    AND mss_package_transactions.package_txn_cashier = mss_employees.employee_id
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_package_transactions.datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                    ORDER BY
                        mss_package_transactions.package_txn_id";


    $query = $this->db->query($sql);

    if ($query) {
      $result = $query->result_array();
      $result_to_send = array();

      for ($i = 0; $i < count($result); $i++) {
        array_push($result_to_send, $result[$i]);
        if ($result[$i]["Settlement Way"] == "Split Payment" && $result[$i]["Payment Mode"] != "Split") {

          $str = $result[$i]["Payment Mode"];

          $someArray = json_decode($str, true);

          $simpler_string = "{";

          $len = count($someArray);

          for ($j = 0; $j < $len; $j++) {
            if ($j == ($len - 1)) {
              $simpler_string .= $someArray[$j]["payment_type"] . " : " . $someArray[$j]["amount_received"];
            } else {
              $simpler_string .= $someArray[$j]["payment_type"] . " : " . $someArray[$j]["amount_received"] . ",";
            }
          }
          $simpler_string .= "}";
          $result_to_send[$i]["Payment Mode"] =  $simpler_string;
        }
      }

      return $this->ModelHelper(true, false, '', $result_to_send);
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetEmployeeWisePerformanceReport($data)
  {
    $sql = "SELECT 
                     mss_transactions.txn_id AS 'Bill No',
                     mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                     mss_customers.customer_mobile AS 'Customer Mobile',
                     mss_customers.customer_name AS 'Customer Name',
                     date(mss_transactions.txn_datetime) AS 'Billing Date',
                     mss_categories.category_name AS 'Category',
                     mss_sub_categories.sub_category_name AS 'Sub-Category',
                     mss_services.service_name AS 'Service',
                     mss_employees.employee_first_name As 'Expert Name',
                     mss_transaction_services.txn_service_discounted_price AS 'Discounted Service Amt',
                     mss_transactions.txn_value AS 'Net Bill Amt',
                     mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                     mss_transactions,
                     mss_transaction_services,
                     mss_employees,
                     mss_customers,
                     mss_categories,
                     mss_sub_categories,
                     mss_services,
                     mss_business_outlets,
                    mss_business_admin
                WHERE
                     mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                     AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                     AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                     AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                     AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                     AND mss_transactions.txn_customer_id = mss_customers.customer_id
                     AND  mss_employees.employee_business_outlet = mss_business_outlets.business_outlet_id
                     AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                     AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                     AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                     AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                     ORDER BY
                        mss_transactions.txn_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetBillWiseDiscountReport($data)
  {
    $sql = "SELECT 
                    mss_transactions.txn_id AS 'Bill No',
                    mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                    mss_customers.customer_mobile AS ' Customer Mobile',
                    mss_customers.customer_name AS 'Customer Name',
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_services.service_name AS 'Service Name',
                    TRUNCATE(mss_services.service_price_inr + ((mss_services.service_gst_percentage/100) * mss_services.service_price_inr),2)  AS 'Service MRP',
                    mss_transaction_services.txn_service_discounted_price AS 'Service Discounted Price',
                    mss_transactions.txn_discount AS 'Net Discount Amt',
                    mss_transactions.txn_value AS 'Net Bill Amt',
                    mss_employees.employee_first_name AS 'Expert First Name',
                    mss_employees.employee_last_name AS 'Expert Last Name',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM 
                    mss_transactions,
                    mss_transaction_services,
                    mss_customers,
                    mss_employees,
                    mss_services,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                ORDER BY
                    mss_transactions.txn_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetStockReportRawMaterial($data)
  {
    $sql = "SELECT 
                    mss_raw_material_categories.raw_material_category_id AS 'Raw Material Category Code',
                    mss_raw_material_categories.raw_material_name AS 'Raw Material Name',
                    mss_raw_material_categories.raw_material_brand AS 'Brand',
                    mss_raw_material_categories.raw_material_type AS 'Material Type',
                    mss_raw_material_stock.rm_entry_date AS 'Entry Date',
                    mss_raw_material_stock.rm_expiry_date AS 'Expiry Date',
                    mss_raw_material_stock.rm_stock AS 'Stock As On date',
                    mss_raw_material_categories.raw_material_unit AS 'Unit',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM 
                    mss_raw_material_categories,
                    mss_raw_material_stock ,
                    mss_business_outlets,
                    mss_business_admin
                WHERE 
                    mss_raw_material_categories.raw_material_category_id = mss_raw_material_stock.rmc_id 
                    AND mss_raw_material_categories.raw_material_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_raw_material_categories.raw_material_is_active = 1";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetStockReportOTC($data)
  {
    $sql = "SELECT 
                    mss_services.service_name AS 'Service',
                    mss_services.service_id AS 'Item Code',
                    mss_services.service_brand AS 'Brand',
                    mss_services.service_type AS 'Type',
                    mss_otc_stock.otc_sku AS 'Current Stock',
                    mss_otc_stock.otc_expiry_date AS 'Expiry Date',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'

                FROM 
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_otc_stock ,
                    mss_business_outlets,
                    mss_business_admin
                WHERE 
                    mss_categories.category_id = mss_sub_categories.sub_category_category_id 
                    AND mss_sub_categories.sub_category_id = mss_services.service_sub_category_id 
                    AND mss_services.service_id = mss_otc_stock.otc_service_id 
                    AND mss_services.service_is_active = 1 
                    AND mss_categories.category_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . " 
                    AND mss_services.service_type = 'otc'
                ORDER BY
                    mss_otc_stock.otc_expiry_date DESC";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetItemWiseCustomerReport($data)
  {
    $sql = "SELECT 
                    mss_transactions.txn_id AS 'Bill No',
                    mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_categories.category_name AS 'Category',
                    mss_sub_categories.sub_category_name 'Sub-Category',
                    mss_services.service_name AS 'Service',
                    mss_transaction_services.txn_service_discounted_price AS 'Discounted Service Price',
                    TRUNCATE(mss_services.service_price_inr + ((mss_services.service_gst_percentage/100) * mss_services.service_price_inr),2)  AS 'Service MRP',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM 
                    mss_transactions,
                    mss_customers,
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_transaction_services,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id =" . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                ORDER BY mss_transactions.txn_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetItemWiseSalesReport($data)
  {
    $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_categories.category_name AS 'Category',
                    mss_sub_categories.sub_category_name AS 'Sub-Category',
                    mss_services.service_name AS 'Service',
                    COUNT(mss_services.service_id) AS 'Bill Count',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Amt',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                    mss_transactions,
                    mss_transaction_services,
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id =" . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                GROUP BY
                    date(mss_transactions.txn_datetime),
                    mss_categories.category_id,
                    mss_sub_categories.sub_category_id,
                    mss_services.service_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetPackageWiseSalesReport($data)
  {
    $sql = "SELECT 
                    date(mss_package_transactions.datetime) AS 'Billing Date',
                    mss_salon_packages.salon_package_name AS 'Package Name',
                    mss_salon_packages.salon_package_type AS 'Type',
                    COUNT(mss_salon_packages.salon_package_id) AS 'Package Sold Count',
                    SUM(mss_package_transactions.package_txn_value) AS 'Bill Amt',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                    mss_package_transactions,
                    mss_salon_packages,
                    mss_transaction_package_details,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
                    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_salon_packages.business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id =" . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_package_transactions.datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                GROUP BY
                    date(mss_package_transactions.datetime),
                    mss_salon_packages.salon_package_name,
                    mss_salon_packages.salon_package_type";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetSubCategoryWiseSalesReport($data)
  {
    $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Billing Date', 
                    mss_categories.category_name AS 'Category', 
                    mss_sub_categories.sub_category_name AS 'Sub-Category',
                    COUNT(mss_sub_categories.sub_category_id) AS 'Total Sub Category',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Amount',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name' 
                FROM 
                    mss_transactions, 
                    mss_transaction_services, 
                    mss_categories, 
                    mss_sub_categories,
                    mss_services,
                    mss_business_outlets,
                    mss_business_admin
                WHERE 
                    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id =" . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . " 
                GROUP BY 
                    date(mss_transactions.txn_datetime), 
                    mss_categories.category_id,
                    mss_sub_categories.sub_category_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetBillWiseSalesReport($data)
  {
    $sql = "SELECT 
                    mss_transactions.txn_id AS 'Bill No',
                    mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_customers.customer_mobile AS 'Mobile No',
                    mss_customers.customer_name AS 'Customer Name',
                    (mss_transactions.txn_discount+mss_transactions.txn_value) AS 'MRP Amt',
                    mss_transactions.txn_discount AS 'Discount',
                    mss_transactions.txn_value AS 'Net Amount',
                    mss_transactions.txn_status AS 'billed=1/canceled=0',
                    mss_transactions.txn_remarks AS 'Remarks',
                    mss_transactions.txn_total_tax AS 'Total Tax (Rs.)',
                    mss_transactions.txn_pending_amount AS 'Pending Amount',
                    mss_transaction_settlements.txn_settlement_way AS 'Settlement Way',
                    mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                    
                FROM 
                    mss_customers,
                    mss_transactions,
                    mss_transaction_settlements,
                    mss_business_outlets,
                    mss_business_admin
                WHERE 
                    mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "";

    $query = $this->db->query($sql);

    if ($query) {
      $result = $query->result_array();
      $result_to_send = array();

      for ($i = 0; $i < count($result); $i++) {
        array_push($result_to_send, $result[$i]);
        if ($result[$i]["Settlement Way"] == "Split Payment" && $result[$i]["Payment Mode"] != "Split") {
          $str = $result[$i]["Payment Mode"];
          $someArray = json_decode($str, true);
          $simpler_string = "{";
          $len = count($someArray);
          for ($j = 0; $j < $len; $j++) {
            if ($j == ($len - 1)) {
              $simpler_string .= $someArray[$j]["payment_type"] . " : " . $someArray[$j]["amount_received"];
            } else {
              $simpler_string .= $someArray[$j]["payment_type"] . " : " . $someArray[$j]["amount_received"] . ",";
            }
          }
          $simpler_string .= "}";
          $result_to_send[$i]["Payment Mode"] =  $simpler_string;
        }
      }

      return $this->ModelHelper(true, false, '', $result_to_send);
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetDateWiseCategorySalesReport($data)
  {
    $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_categories.category_name AS 'Category Name',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Sales(Rs.)',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM 
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_transactions,
                    mss_transaction_services,
                    mss_business_outlets,
                    mss_business_admin 
                WHERE
                      mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                  AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
                  AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                  AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                  AND mss_categories.category_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id =" . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                  AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                GROUP BY 
                    date(mss_transactions.txn_datetime), 
                    mss_categories.category_id";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetPaymentWiseReport($data)
  {
    $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Payment Date',          
                    mss_transaction_settlements.txn_settlement_way AS 'Settlement Way',
                    SUM(mss_transactions.txn_value) AS 'Total Amount',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM 
                   mss_transactions, 
                   mss_transaction_settlements, 
                   mss_customers ,
                   mss_business_outlets,
                  mss_business_admin
                WHERE 
                    mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id 
                    AND mss_transactions.txn_customer_id = mss_customers.customer_id 
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                GROUP BY 
                    date(mss_transactions.txn_datetime),
                    mss_transaction_settlements.txn_settlement_way";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetVirtualWalletReport($data)
  {
    $sql = "SELECT
                    mss_customers.customer_name As 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_customers.customer_virtual_wallet AS 'Virtual Wallet',
                    IFNULL(mss_customers.customer_wallet_expiry_date,'') AS 'Expiry Date',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                     mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_customers.customer_virtual_wallet > 0";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  private function GetPendingAmountReport($data)
  {
    $sql = "SELECT 
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_customers.customer_pending_amount AS 'Pending Amount',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }


  private function GetPendingAmountTransactionReport($data)
  {
    $sql = "SELECT 
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_pending_amount_tracker.pending_amount_submitted AS 'Cleared Pending Amount',
                    mss_pending_amount_tracker.pending_amount_outstanding AS 'Pending Amount Outstanding',
                    mss_pending_amount_tracker.date_time AS 'Date & Time',
                    mss_business_outlets.business_outlet_city as 'Outlet Branch',
                    mss_business_outlets.business_outlet_name as 'Outlet Name',
                    mss_business_admin.business_admin_first_name as 'Admin Name'
                FROM
                    mss_customers,
                    mss_pending_amount_tracker,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_pending_amount_tracker.customer_id = mss_customers.customer_id
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND date(mss_pending_amount_tracker.date_time) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  //Appointment Report
  private function GetAppointmentReport($data)
  {
    $sql = "SELECT
			mss_appointments.appointment_date AS 'Appointment Date',
			mss_appointments.appointment_start_time AS 'Appointment Time',
			mss_appointments.appointment_status AS 'Billed=1/Canceled=0',
			mss_customers.customer_name AS 'Customer Name',
			mss_customers.customer_mobile AS 'Mobile',
			mss_employees.employee_first_name AS 'Expert',
			mss_services.service_name AS 'Service',
			mss_services.service_price_inr AS 'MRP',
      mss_business_outlets.business_outlet_city as 'Outlet Branch',
      mss_business_outlets.business_outlet_name as 'Outlet Name',
      mss_business_admin.business_admin_first_name as 'Admin Name'
			FROM
				mss_appointments,
				mss_appointment_services,
				mss_customers,
				mss_employees,
				mss_services,
        mss_business_outlets,
        mss_business_admin
			WHERE
				mss_appointments.customer_id=mss_customers.customer_id
			AND	
				mss_appointment_services.appointment_id=mss_appointments.appointment_id
			AND
				mss_appointment_services.service_id=mss_services.service_id
			AND	
				mss_appointment_services.expert_id=mss_employees.employee_id
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND mss_business_admin.business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
			AND date(mss_appointments.appointment_date) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "";


    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function GetTopCardsData($data)
  {
    switch ($data['type']) {
      case 'sales':
        $sql = "SELECT 
                        SUM(mss_transaction_settlements.txn_settlement_amount_received) AS sales
                    	FROM
                        mss_transactions,
						mss_transaction_settlements,
                        mss_customers
                        WHERE
						mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
						AND
						mss_transactions.txn_customer_id = mss_customers.customer_id
						AND date(mss_transactions.txn_datetime) = date(now())
						AND mss_transactions.txn_status=1
						AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . " 
						AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "";
        //

        //
        break;
      case 'yesterday_sales':
        $sql = "SELECT 
							SUM(mss_transaction_settlements.txn_settlement_amount_received) AS 'yesterday_sales'
							FROM
							mss_transactions,
							mss_transaction_settlements,
							mss_customers
							WHERE
							mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
							AND
							mss_transactions.txn_customer_id = mss_customers.customer_id
							AND date(mss_transactions.txn_datetime) = SUBDATE(NOW(), 1)
							AND mss_transactions.txn_status=1
							AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . " 
							AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "";
        //

        //
        break;
      case 'customer_count':
        $sql = "SELECT 
				COUNT(*) as 'customer_count' 
			FROM
				(
				SELECT
				DISTINCT mss_customers.customer_id 
				FROM 
					mss_transactions,mss_customers
				WHERE
				mss_transactions.txn_customer_id=mss_customers.customer_id 
				AND
				mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
				AND mss_customers.customer_business_outlet_id =" . $this->db->escape($data['business_outlet_id']) . " 
				AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now()) )T1";
        break;
      case 'new_customer':
        $sql = "SELECT 
                            COUNT(*) as 'new_customer' 
                        FROM
                            (SELECT
                                COUNT(*) as cnt
                            FROM 
                                mss_customers,
                                mss_transactions
                            WHERE 
                                mss_transactions.txn_customer_id = mss_customers.customer_id
                                AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . " 
                                AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())
                            GROUP BY
                                mss_transactions.txn_customer_id
                            HAVING
                                cnt =1
                            ) T1";
        break;
      case 'total_visits':
        $sql = "SELECT 
                            COUNT(mss_transactions.txn_id) as 'total_visits'
                        FROM 
                            mss_transactions, 
                            mss_customers 
                        WHERE 
                            mss_transactions.txn_customer_id = mss_customers.customer_id 
                            AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . " 
                            AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . " 
                            AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())";
        break;
      case 'existing_customer':
        $sql = "SELECT 
                            COUNT(*) as 'existing_customer' 
                        FROM
                            (SELECT
                                COUNT(*) as cnt
                            FROM 
                                mss_customers,
                                mss_transactions
                            WHERE 
                                mss_transactions.txn_customer_id = mss_customers.customer_id
                                AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . " 
                                AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())
                            GROUP BY
                                mss_transactions.txn_customer_id
                            HAVING
                                cnt > 1
                            ) T1";
        break;
      case 'expenses':
        $sql = " SELECT 
                            SUM(mss_expenses.amount) AS 'expenses'
                        FROM 
                            mss_expenses,
                            mss_expense_types
                        WHERE
                            mss_expenses.expense_type_id = mss_expense_types.expense_type_id
                            AND mss_expense_types.expense_type_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                            AND mss_expense_types.expense_type_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                            AND mss_expenses.expense_date = date(now())";
        break;
      case 'yest_expenses':
        $sql = "SELECT 
							SUM(mss_expenses.amount) AS 'yest_expenses'
						FROM 
							mss_expenses,
							mss_expense_types
						WHERE
							mss_expenses.expense_type_id = mss_expense_types.expense_type_id
							AND mss_expense_types.expense_type_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
							AND mss_expense_types.expense_type_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
							AND mss_expenses.expense_date = SUBDATE(CURDATE(),1)";
        break;
      default:
        return $this->ModelHelper(false, true, "Wrong choice!");
        break;
    }


    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->row_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function GetSalesPaymentWiseData($where)
  {
    $data = array(
      'cash'           => 0,
      'credit_card'    => 0,
      'debit_card'     => 0,
      'google_pay'     => 0,
      'phone_pe'       => 0,
      'paytm'          => 0,
      'virtual_wallet' => 0,
      'others'         => 0
    );

    //Calculate Full Payment Sales for Today
    $sql1 = "SELECT
						mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode',
						SUM(mss_transaction_settlements.txn_settlement_amount_received) AS 'Sum Amount'
					FROM 
						mss_transactions,
						mss_transaction_settlements,
						mss_customers
					WHERE
		 			mss_transactions.txn_status=1
					AND mss_transactions.txn_customer_id = mss_customers.customer_id
					AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
					AND mss_transaction_settlements.txn_settlement_way = 'Full Payment'
					AND mss_customers.customer_business_admin_id = " . $this->db->escape($where['business_admin_id']) . "
					AND mss_customers.customer_business_outlet_id = " . $this->db->escape($where['business_outlet_id']) . "
					AND date(mss_transactions.txn_datetime) = date(now())
					GROUP BY mss_transaction_settlements.txn_settlement_payment_mode";

    $query1 = $this->db->query($sql1);

    $data_full_payment = $query1->result_array();
    foreach ($data_full_payment as $k) {
      switch ($k['Payment Mode']) {
        case 'Cash':
          $data['cash']           += (int) $k['Sum Amount'];
          break;
        case 'Credit_Card':
          $data['credit_card']    += (int) $k['Sum Amount'];
          break;
        case 'Debit_Card':
          $data['debit_card']     += (int) $k['Sum Amount'];
          break;
        case 'Paytm':
          $data['paytm']          += (int) $k['Sum Amount'];
          break;
        case 'Phonepe':
          $data['phone_pe']       += (int) $k['Sum Amount'];
          break;
        case 'Google_Pay':
          $data['google_pay']     += (int) $k['Sum Amount'];
          break;
        case 'Virtual_Wallet':
          $data['virtual_wallet'] += (int) $k['Sum Amount'];
          break;
        default:
          $data['others']         += (int) $k['Sum Amount'];
          break;
      }
    }


    $sql2 = "SELECT 
                    mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode'
                FROM 
                    mss_transactions, 
                    mss_transaction_settlements, 
                    mss_customers 
                WHERE 
                    mss_transactions.txn_customer_id = mss_customers.customer_id 
                    AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                    AND mss_transaction_settlements.txn_settlement_way = 'Split Payment'
                    AND mss_customers.customer_business_admin_id = " . $this->db->escape($where['business_admin_id']) . "
                    AND mss_customers.customer_business_outlet_id = " . $this->db->escape($where['business_outlet_id']) . "
                    AND date(mss_transactions.txn_datetime) = date(now())";

    $query2 = $this->db->query($sql2);

    $data_split_payment = $query2->result_array();

    foreach ($data_split_payment as $k) {
      $str = $k["Payment Mode"];

      $someArray = json_decode($str, true);

      $len = count($someArray);

      for ($j = 0; $j < $len; $j++) {
        switch ($someArray[$j]["payment_type"]) {
          case 'Cash':
            $data['cash']           += (int) $someArray[$j]["amount_received"];
            break;
          case 'Credit_Card':
            $data['credit_card']    += (int) $someArray[$j]["amount_received"];
            break;
          case 'Debit_Card':
            $data['debit_card']     += (int) $someArray[$j]["amount_received"];
            break;
          case 'Paytm':
            $data['paytm']          += (int) $someArray[$j]["amount_received"];
            break;
          case 'Phonepe':
            $data['phone_pe']       += (int) $someArray[$j]["amount_received"];
            break;
          case 'Google_Pay':
            $data['google_pay']     += (int) $someArray[$j]["amount_received"];
            break;
          case 'Virtual_Wallet':
            $data['virtual_wallet'] += (int) $someArray[$j]["amount_received"];
            break;
          default:
            $data['others']         += (int) $someArray[$j]["amount_received"];
            break;
        }
      }
    }

    return $this->ModelHelper(true, false, '', $data);
  }

  public function GetLowStockItems($data)
  {
    $sql = "SELECT
                    mss_services.service_name as service_name,
                    mss_otc_stock.otc_sku as qty
                FROM
                    mss_otc_stock,
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_otc_stock.otc_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . " 
                    AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . " 
                    AND mss_services.service_type = 'otc'
                    AND mss_otc_stock.otc_sku < 15";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function GetGenderDistribution($data)
  {
    $sql = "SELECT 
                    mss_customers.customer_title, 
                    COUNT(mss_customers.customer_id) AS count_gender
                FROM 
                    mss_customers
                WHERE
                    mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                GROUP BY 
                    mss_customers.customer_title";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function GetAgeDistribution($data)
  {
    $sql = "SELECT 
                    t.age_group, 
                    COUNT(*) AS age_count
                FROM
                (
                    SELECT
                        CASE WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE())    BETWEEN 20 AND 25 THEN '20-25'
                             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
                             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 36 AND 45 THEN '36-45'
                             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 46 AND 55 THEN '46-55'
                             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) > 55 THEN '>55'
                             ELSE 'Others'
                        END AS age_group
                    FROM 
                        mss_customers
                    WHERE 
                        mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . " 
                ) t
                GROUP BY t.age_group";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }


  public function GetRevenueBarChart($data)
  {
    $sql = "SELECT 
                    date(mss_transactions.txn_datetime) as bill_date, 
                    SUM(mss_transactions.txn_value) AS sales 
                FROM 
                    mss_transactions,
                    mss_customers 
                WHERE 
                    mss_transactions.txn_customer_id = mss_customers.customer_id 
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                    AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                GROUP BY 
                    date(mss_transactions.txn_datetime)
                ORDER BY
                  date(mss_transactions.txn_datetime)
                LIMIT 15";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  //package revenue
  public function GetPackageRevenueBarChart($data)
  {
    $sql = "SELECT 
					date(mss_package_transactions.datetime) as bill_date, 
					SUM(mss_package_transactions.package_txn_value) AS sales 
			FROM 
					mss_package_transactions,
					mss_customers 
			WHERE 
				mss_package_transactions.package_txn_customer_id= mss_customers.customer_id 
					AND date(mss_package_transactions.datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
					AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
					AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
			GROUP BY 
					date(mss_package_transactions.datetime)
			ORDER BY
				date(mss_package_transactions.datetime)
			LIMIT 15";

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function GetCustomerBarChart($data)
  {
    $sql = "SELECT 
                   date(mss_customers.customer_added_on) as add_date,
                   COUNT(mss_customers.customer_id) as total
                FROM 
                    mss_customers
                WHERE
                    mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND mss_customers.customer_added_on BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                GROUP BY
                    date(mss_customers.customer_added_on)
                ORDER BY
                    date(mss_customers.customer_added_on)
                LIMIT 7";


    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function GetVisitsBarChart($data)
  {
    $sql = "SELECT
                    date(mss_transactions.txn_datetime) as visit_date,
                    COUNT(mss_transactions.txn_id) AS 'total_visits'
                FROM 
                    mss_transactions,
                    mss_customers
                WHERE
                    mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_customers.customer_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                    AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                    AND date(mss_transactions.txn_datetime) BETWEEN " . $this->db->escape($data['from_date']) . " AND " . $this->db->escape($data['to_date']) . "
                GROUP BY
                    date(mss_transactions.txn_datetime)
                ORDER BY
                    date(mss_transactions.txn_datetime)
                LIMIT 7";


    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function BarChartYearly($data)
  {
    $sql = "";
    switch ($data['yearly_kpi']) {
      case 'revenue':
        if (!empty($data['service_id']) && isset($data['service_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                SUM(mss_transaction_services.txn_service_discounted_price) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_services.service_id = " . $this->db->escape($data['service_id']) . "
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        } elseif (!empty($data['sub_category_id']) && isset($data['sub_category_id'])) {
          $sql = "SELECT 
                              YEAR(date(mss_transactions.txn_datetime)) AS year,
                              MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                              SUM(mss_transaction_services.txn_service_discounted_price) as yearly_data
                          FROM 
                              mss_transactions,
                              mss_transaction_services,
                              mss_services,
                              mss_sub_categories,
                            mss_categories
                          WHERE
                              mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                              AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                              AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                              AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                              AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                              AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                              AND mss_sub_categories.sub_category_id = " . $this->db->escape($data['sub_category_id']) . "
                              AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                          GROUP BY
                              YEAR(date(mss_transactions.txn_datetime)),
                              MONTH(date(mss_transactions.txn_datetime))";
        } elseif (!empty($data['category_id']) && isset($data['category_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                SUM(mss_transaction_services.txn_service_discounted_price) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_categories.category_id = " . $this->db->escape($data['category_id']) . " 
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        }
        break;
      case 'customers':
        if (!empty($data['service_id']) && isset($data['service_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                COUNT(DISTINCT mss_transactions.txn_customer_id) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_services.service_id = " . $this->db->escape($data['service_id']) . "
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        } elseif (!empty($data['sub_category_id']) && isset($data['sub_category_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                COUNT(DISTINCT mss_transactions.txn_customer_id) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_sub_categories.sub_category_id = " . $this->db->escape($data['sub_category_id']) . "
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        } elseif (!empty($data['category_id']) && isset($data['category_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                COUNT(DISTINCT mss_transactions.txn_customer_id) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_categories.category_id = " . $this->db->escape($data['category_id']) . "
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        }
        break;
      case 'visits':
        if (!empty($data['service_id']) && isset($data['service_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                COUNT(mss_transaction_services.txn_service_txn_id) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_services.service_id = " . $this->db->escape($data['service_id']) . "
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        } elseif (!empty($data['sub_category_id']) && isset($data['sub_category_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                COUNT(mss_transaction_services.txn_service_txn_id) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                                AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                                AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                                AND mss_sub_categories.sub_category_id = " . $this->db->escape($data['sub_category_id']) . "
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        } elseif (!empty($data['category_id']) && isset($data['category_id'])) {
          $sql = "SELECT 
                                YEAR(date(mss_transactions.txn_datetime)) AS year,
                                MONTHNAME(date(mss_transactions.txn_datetime)) as month,
                                COUNT(mss_transaction_services.txn_service_txn_id) as yearly_data
                            FROM 
                                mss_transactions,
                                mss_transaction_services,
                                mss_services,
                                mss_sub_categories,
                                mss_categories
                            WHERE
                            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                            AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                            AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                            AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                            AND mss_categories.category_business_admin_id = " . $this->db->escape($data['business_admin_id']) . "
                            AND mss_categories.category_business_outlet_id = " . $this->db->escape($data['business_outlet_id']) . "
                            AND mss_categories.category_id = " . $this->db->escape($data['category_id']) . "
                            AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
        }
        break;
      default:
        return $this->ModelHelper(false, true, "Wrong choice!");
    }

    $query = $this->db->query($sql);

    if ($query) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  //ji
 
  

  //master Admin
  public function BusinessAdminPermission($where)
  {
    $sql = "SELECT mss_business_admin.business_admin_first_name,
		mss_business_admin.business_admin_id,
		mss_user_permission.*
		FROM
		mss_business_admin,
		mss_user_permission
		WHERE
		mss_business_admin.business_admin_id = mss_user_permission.business_admin_id
		AND
		mss_business_admin.business_master_admin_id =" . $this->db->escape($where['business_master_admin_id']) . " ";

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function EditCategoryPermission($where)
  {
    $data = $this->Update($where['edit_category'], 'mss_user_permission', $where['business_admin_id']);
    $sql = "SELECT mss_business_admin.business_admin_first_name,
      mss_business_admin.business_admin_id,
      mss_user_permission.*
      FROM
      mss_business_admin,
      mss_user_permission
      WHERE
      mss_business_admin.business_admin_id = mss_user_permission.business_admin_id
      AND
      mss_business_admin.business_master_admin_id =" . $this->db->escape($where['business_master_admin_id']) . " ";

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }

  public function CurrentMonthSales($data)
  {

    $query = "SELECT sum(sales) + sum(packages) as 'total_sales'
        FROM
        (SELECT sum(mss_transactions.txn_value)as 'sales',
                 mss_business_outlets.business_outlet_id as 'outlet_id'
                 FROM
                     mss_transaction_settlements,
                     mss_transactions,
                     mss_customers,
                     mss_business_outlets,
                     mss_business_admin
                 WHERE
                     mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
                 AND
                     mss_customers.customer_id = mss_transactions.txn_customer_id
                 AND
                     mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                 AND
                     mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                 AND
                     mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
                 AND
                     mss_transactions.txn_datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
         GROUP BY
             mss_business_outlets.business_outlet_id
         ORDER BY
             mss_business_outlets.business_outlet_id
        
        )sales,
        (SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                 mss_business_outlets.business_outlet_id as 'outlet_id'
            FROM
            mss_package_transactions,
            mss_package_transaction_settlements,
            mss_customers,
            mss_business_outlets,
            mss_business_admin
               WHERE
            mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
            AND
            mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
            AND
            mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
            AND
            mss_package_transactions.datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
        )packages";


    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, "DB error!");
    }
  }
  public function PreviousMonthSales($data)
  {
    $query = "SELECT sum(sales) + sum(packages) as 'total_sales'
        FROM
        (SELECT sum(mss_transactions.txn_value)as 'sales',
                 mss_business_outlets.business_outlet_id as 'outlet_id'
                 FROM
                     mss_transaction_settlements,
                     mss_transactions,
                     mss_customers,
                     mss_business_outlets,
                     mss_business_admin
                 WHERE
                     mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
                 AND
                     mss_customers.customer_id = mss_transactions.txn_customer_id
                 AND
                     mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                 AND
                     mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                 AND
                     mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
                 AND
                     mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)
        )sales,
        (SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                 mss_business_outlets.business_outlet_id as 'outlet_id'
            FROM
            mss_package_transactions,
            mss_package_transaction_settlements,
            mss_customers,
            mss_business_outlets,
            mss_business_admin
               WHERE
            mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
            AND
            mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
            AND
            mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
            AND
            mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)
        )packages";

    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error!');
    }
  }
  public function PreviousMonthTotalSales($data)
  {
    $query = "SELECT (SUM(sales) + SUM(packages)) as 'total_sales'
        FROM
        (SELECT SUM(mss_transactions.txn_value)as 'sales',
                 mss_business_outlets.business_outlet_id as 'outlet_id'
                 FROM
                     mss_transaction_settlements,
                     mss_transactions,
                     mss_customers,
                     mss_business_outlets,
                     mss_business_admin
                 WHERE
                     mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
                 AND
                     mss_customers.customer_id = mss_transactions.txn_customer_id
                 AND
                     mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                 AND
                     mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                 AND
                     mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
                 AND
                     mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY
        )sales,
        (SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                 mss_business_outlets.business_outlet_id as 'outlet_id'
            FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin
            WHERE
                mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
            AND
                mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
            AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
                mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
            AND
                mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
            AND
                mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY
        )packages";
    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error!');
    }
  }
  public function AverageCurrentMonthSales($data)
  {
    $query = "SELECT ROUND((sum(services)+sum(products)+sum(packages))/(DAYOFMONTH(CURRENT_DATE)),2) AS 'avg_sales'
        FROM
        (SELECT
            SUM(mss_transactions.txn_value) AS 'services'
            FROM
                    mss_transactions,
                    mss_transaction_services,
                    mss_employees,
                    mss_customers,
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_business_outlets,
                    mss_business_admin,
                    mss_transaction_settlements
            WHERE
                    mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
                    AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
                    AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_type = 'Service'
                    AND mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
                    AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
                    AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data) . "
                    AND date(mss_transactions.txn_datetime) >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01'))services,
         (SELECT
            SUM(mss_transactions.txn_value) AS 'products'
            FROM
                    mss_transactions,
                    mss_transaction_services,
                    mss_employees,
                    mss_customers,
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_business_outlets,
                    mss_business_admin,
                    mss_transaction_settlements
            WHERE
                    mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
                    AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
                    AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_type = 'Products'
                    AND mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
                    AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
                    AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data) . "
                    AND date(mss_transactions.txn_datetime) >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01'))products,
             (SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
            mss_business_outlets.business_outlet_id as 'outlet_id'
            FROM
            mss_package_transactions,
            mss_package_transaction_settlements,
            mss_customers,
            mss_business_outlets,
            mss_business_admin
                WHERE
            mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
            AND
            mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
            AND
            mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
            AND
            mss_package_transactions.datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH))packages";

    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error!');
    }
  }
  public function AveragePreviousMonthSales($data)
  {
    $query = "SELECT ROUND((sum(services)+sum(products)+sum(packages))/(DAYOFMONTH(LAST_DAY((CURRENT_DATE)-INTERVAL 1 MONTH))),2) AS 'avg_sales'
        FROM 
        (SELECT SUM(mss_transactions.txn_value) AS 'services' 
         FROM 
         mss_transactions, 
         mss_transaction_services, 
         mss_employees, 
         mss_customers, 
         mss_categories, 
         mss_sub_categories, 
         mss_services, 
         mss_business_outlets, 
         mss_business_admin, 
         mss_transaction_settlements 
         WHERE mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
         AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id 
         AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id 
         AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
         AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
         AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
         AND mss_categories.category_type = 'Service' 
         AND mss_transactions.txn_customer_id = mss_customers.customer_id 
         AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id 
         AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
         AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
         AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01')AND DATE_SUB(LAST_DAY(CURRENT_DATE),INTERVAL DAY(LAST_DAY(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY)
         services, 
         (SELECT SUM(mss_transactions.txn_value) AS 'products' 
         FROM 
         mss_transactions, 
         mss_transaction_services, 
         mss_employees, 
         mss_customers, 
         mss_categories, 
         mss_sub_categories, 
         mss_services, 
         mss_business_outlets, 
         mss_business_admin, 
         mss_transaction_settlements 
         WHERE mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
         AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id 
         AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id 
         AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
         AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id 
         AND mss_sub_categories.sub_category_category_id = mss_categories.category_id 
         AND mss_categories.category_type = 'Products' 
         AND mss_transactions.txn_customer_id = mss_customers.customer_id 
         AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id 
         AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id 
         AND mss_business_admin.business_master_admin_id =1 
         AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY)
         products, 
         (SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages'
          FROM 
          mss_package_transactions, 
          mss_package_transaction_settlements, 
          mss_customers, 
          mss_business_outlets, 
          mss_business_admin 
          WHERE 
          mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id 
          AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id 
          AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id 
          AND mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id 
          AND mss_business_admin.business_master_admin_id=1 
          AND mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY)
          packages";

    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastFifteenDaySalesByType($data)
  {
    $query="SELECT sum(mss_transaction_services.txn_service_discounted_price) as total_sales ,
            date(mss_transactions.txn_datetime) as bill_date,
            count(mss_transaction_settlements.txn_settlement_txn_id) as bill_count
              FROM
              mss_services,
              mss_business_outlets,
              mss_business_admin,
              mss_transactions,
              mss_transaction_settlements,
              mss_transaction_services,
              mss_customers
              WHERE
              mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
              AND
              mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
              AND
              mss_transaction_services.txn_service_service_id = mss_services.service_id
              AND
              mss_services.service_type = ".$this->db->escape($data['type'])."
              AND
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_admin.business_master_admin_id = ".$this->db->escape($data['master_admin_id'])."
              AND
              mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
              GROUP BY
                date(mss_transactions.txn_datetime)
              ORDER BY
                date(mss_transactions.txn_datetime)
              LIMIT 15";
    // $query = "SELECT date(mss_transactions.txn_datetime) as bill_date,sum(mss_transactions.txn_value) as total_sales 
    //     FROM 
    //         `mss_transaction_settlements`,mss_transactions,mss_customers,mss_business_admin
    //     WHERE 
    //         mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
    //     AND
    //         mss_transactions.txn_customer_id = mss_customers.customer_id
    //     AND
    //         mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
    //     AND
    //         mss_business_admin.business_master_admin_id = ".$this->db->escape($data)."
    //     AND
    //         mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
    //     GROUP BY
    //         date(mss_transactions.txn_datetime)
    //     ORDER BY
    //         date(mss_transactions.txn_datetime)
    //     LIMIT 15";
    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error!');
    }
  }
  public function LastFifteenDayProductSales($data)
  {
    $query="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'product_sales',
    date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'product_date'
      FROM
      mss_services,
      mss_business_outlets,
      mss_business_admin,
      mss_transactions,
      mss_transaction_settlements,
      mss_transaction_services,
      mss_customers
      WHERE
      mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
      AND
      mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
      AND
      mss_transaction_services.txn_service_service_id = mss_services.service_id
      AND
      mss_services.service_type = 'otc'
      AND
      mss_transactions.txn_customer_id = mss_customers.customer_id
      AND
      mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
      AND
      mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
      AND
      mss_business_admin.business_master_admin_id = ".$this->db->escape($data)."
      AND
      mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
      GROUP BY
        date(mss_transactions.txn_datetime)
      ORDER BY
        date(mss_transactions.txn_datetime)
      LIMIT 15";
    // $query = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'product_sales',
    //     date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'product_date'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //        mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //        AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //        AND mss_categories.category_type = 'Products'
    //        AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //        AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //        AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
    //        AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data) . "
    //        AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 15 day)
    //        GROUP BY
    //             date(mss_transactions.txn_datetime)
    //        ORDER BY
    //             date(mss_transactions.txn_datetime)
    //         LIMIT 15";
    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error!');
    }
  }
  public function LastFifteenDayPackageSales($data)
  {
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                DATE_FORMAT(mss_package_transactions.datetime,'%Y-%m-%d')as 'pack_date',
                count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
                FROM
                    mss_package_transactions,
                    mss_package_transaction_settlements,
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                AND
                    mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND
                    mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                    mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
                AND
                    mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
                AND
                    mss_package_transactions.datetime >= date_sub(current_date,interval 15 day)
                GROUP BY 
                    month(mss_package_transactions.datetime)
                ORDER BY
                    year(mss_package_transactions.datetime)DESC,month(mss_package_transactions.datetime)DESC
                LIMIT 15";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CustomerVisitsTillDate($data)
  {
    $sql = "SELECT count(mss_transactions.txn_id) as total_visit 
        FROM 
            `mss_transaction_settlements`,mss_transactions,mss_customers,mss_business_admin
        WHERE 
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
        AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
            mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND
            mss_business_admin.business_master_admin_id = 1
        AND
            mss_transactions.txn_datetime <= CURRENT_DATE +INTERVAL 1 DAY
        AND
            month(mss_transactions.txn_datetime) = month(CURRENT_DATE)
        AND
            YEAR(mss_transactions.txn_datetime) = YEAR(CURRENT_DATE)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CustomerVisitPreviousMonthTillDate($data)
  {
    $sql = "SELECT count(mss_transactions.txn_id) as total_visit 
        FROM 
            `mss_transaction_settlements`,mss_transactions,mss_customers,mss_business_admin
        WHERE 
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
        AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
            mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND
            mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
        AND
        date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CustomerVisitPreviousMonth($data)
  {
    $sql = "SELECT count(mss_transactions.txn_id) as total_visit
        FROM 
            `mss_transaction_settlements`,mss_transactions,mss_customers,mss_business_admin
        WHERE 
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
        AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
            mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND
            mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
        AND
            month(mss_transactions.txn_datetime) = month(CURRENT_DATE)-1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetTodaySalesByType($data, $madmin_id)
  {
    $sql = "SELECT SUM(mss_transaction_services.txn_service_discounted_price) AS 'total_sales'
            FROM
              mss_services,
              mss_business_outlets,
              mss_business_admin,
              mss_transactions,
              mss_transaction_settlements,
              mss_transaction_services,
              mss_customers
            WHERE
              mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
              mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
              mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
              mss_services.service_type = ".$this->db->escape($data)."
            AND
              mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
              mss_business_admin.business_master_admin_id = ".$this->db->escape($madmin_id)."
            AND
              mss_transactions.txn_datetime >= CURRENT_DATE";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetTodayPackSales($data){
    $sql = "SELECT 
        SUM(mss_package_transaction_settlements.amount_received) AS package_sales
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) = date(now())
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCustVisitToday($data){
    $sql = "SELECT COUNT(*) as 'customer_count' 
            FROM
            (
            SELECT
            DISTINCT mss_customers.customer_id 
            FROM 
                mss_transactions,mss_customers,mss_business_outlets,mss_business_admin
            WHERE
            mss_transactions.txn_customer_id=mss_customers.customer_id 
            AND
            mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
            AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND mss_business_admin.business_admin_id = " . $this->db->escape($data) . "
            AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now()) )T1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetNewCustVisitToday($data){
    $sql = "SELECT 
        COUNT(*) as 'new_customer' 
         FROM
            (SELECT
                COUNT(*) as cnt
            FROM 
                mss_customers,
                mss_transactions,
            mss_business_outlets,
            mss_business_admin
            WHERE 
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND 
            mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
                AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())
            GROUP BY
                mss_transactions.txn_customer_id
            HAVING
                cnt = 1
            ) T1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetExistCustVisitToday($data){
    $sql = "SELECT 
        COUNT(*) as 'existing_customer' 
        FROM
            (SELECT
                COUNT(*) as cnt
            FROM 
                mss_customers,
                mss_transactions,
            mss_business_outlets,
            mss_business_admin
            WHERE 
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND 
            mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
                AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())
            GROUP BY
                mss_transactions.txn_customer_id
            HAVING
                cnt > 1
            ) T1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetTodayCountBySerAndProd($data){
    $sql = "SELECT 
        count(mss_transactions.txn_value) AS total_bill
        FROM
        mss_transactions,
        mss_transaction_settlements,
        mss_customers,
        mss_business_admin,
        mss_business_outlets
        WHERE
        mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) = date(now())
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetTodayCountByPack($data){
    $sql = "SELECT 
        count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) = date(now())
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCurrentBillCount($data){
    $sql = "SELECT (sum(sales_bill)+sum(package_count)) AS total_bill
        FROM
        (SELECT 
        count(mss_transactions.txn_value) AS sales_bill
        FROM
        mss_transactions,
        mss_transaction_settlements,
        mss_customers,
        mss_business_admin,
        mss_business_outlets
        WHERE
        mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) >= DATE_FORMAT(now(),'%Y-%m-01')
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . ")sales_bill,
        (SELECT 
        count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) >= DATE_FORMAT(now(),'%Y-%m-01')
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . ")package_bill";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetPrevTillBillCount($data){
    $sql = "SELECT (sum(sales_bill)+sum(package_count)) AS total_bill
              FROM
              (SELECT 
              count(mss_transactions.txn_value) AS sales_bill
              FROM
              mss_transactions,
              mss_transaction_settlements,
              mss_customers,
              mss_business_admin,
              mss_business_outlets
              WHERE
              mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
              AND
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(now()-INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)
              AND mss_transactions.txn_status=1
              AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
              AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . ")sales_bill,
              (SELECT 
              count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
              FROM
              mss_package_transactions,
              mss_package_transaction_settlements,
              mss_customers,
              mss_business_outlets,
              mss_business_admin
              WHERE
              mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
              mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
              AND date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(now()-INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)
              AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
              AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . ")package_bill";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetPrevBillCount($data){
    $sql = "SELECT (sum(sales_bill)+sum(package_count)) AS total_bill
            FROM
            (SELECT 
            count(mss_transactions.txn_value) AS sales_bill
            FROM
            mss_transactions,
            mss_transaction_settlements,
            mss_customers,
            mss_business_admin,
            mss_business_outlets
            WHERE
            mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY
            AND mss_transactions.txn_status=1
            AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
            AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . ")sales_bill,
            (SELECT 
            count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
            FROM
            mss_package_transactions,
            mss_package_transaction_settlements,
            mss_customers,
            mss_business_outlets,
            mss_business_admin
            WHERE
            mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
            mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
            AND date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY
            AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
            AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . ")package_bill";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetPreviousPackTillDate($data){
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages'
                FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin
                    WHERE
                mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                AND
                mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
                AND
                mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
                AND
                mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetPreviousPackSales($data){
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages'
                FROM
                    mss_package_transactions,
                    mss_package_transaction_settlements,
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                AND
                    mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND
                    mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                    mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
                AND
                    mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
                AND
                    mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CurrentMonthPackSales($data){
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
        mss_business_outlets.business_outlet_id as 'outlet_id'
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
            WHERE
        mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
        AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
        AND
        mss_business_admin.business_master_admin_id=" . $this->db->escape($data) . "
        AND
        mss_package_transactions.datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CurrentMonthServiceSales($data){
    $sql = "SELECT sum(mss_transactions.txn_value)as 'sales',
        mss_business_outlets.business_outlet_id as 'outlet_id'
        FROM
            mss_transaction_settlements,
            mss_transactions,
            mss_customers,
            mss_business_outlets,
            mss_business_admin
        WHERE
            mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
        AND
            mss_customers.customer_id = mss_transactions.txn_customer_id
        AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
            mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND
            mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
        AND
            mss_transactions.txn_datetime >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCurrentMonthSalesByType($data, $madmin_id){
    $sql ="SELECT sum(mss_transactions.txn_value) AS 'total_sales' FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data)."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = ".$this->db->escape($madmin_id)."
            AND
            mss_transactions.txn_datetime >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    // $sql = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'total_sales'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //             mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //             AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //             AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //             AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //             AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //             AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //             AND mss_categories.category_type = " . $this->db->escape($data) . "
    //             AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //             AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //             AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
    //             AND mss_business_admin.business_master_admin_id =" . $this->db->escape($madmin_id) . "
    //             AND date(mss_transactions.txn_datetime) >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function PreviousMonthTillSalesByType($data, $maadmin_id){
    $sql= "SELECT sum(mss_transactions.txn_value)AS 'total_sales' FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data)."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = ". $this->db->escape($maadmin_id) ."
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    // $sql = "SELECT
        // SUM(mss_transactions.txn_value) AS 'total_sales'
        // FROM
        //         mss_transactions,
        //         mss_transaction_services,
        //         mss_employees,
        //         mss_customers,
        //         mss_categories,
        //         mss_sub_categories,
        //         mss_services,
        //         mss_business_outlets,
        //         mss_business_admin,
        //         mss_transaction_settlements
        // WHERE
        //         mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
        //         AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
        //         AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
        //         AND mss_transaction_services.txn_service_service_id = mss_services.service_id
        //         AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
        //         AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        //         AND mss_categories.category_type = " . $this->db->escape($data) . "
        //         AND mss_transactions.txn_customer_id = mss_customers.customer_id
        //         AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
        //         AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
        //         AND mss_business_admin.business_master_admin_id =" . $this->db->escape($maadmin_id) . "
        //         AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function PreviousMonthSalesByType($data, $ma_admin_id){
    $sql ="SELECT sum(mss_transactions.txn_value) AS 'total_sales' FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data)."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id =".$this->db->escape($ma_admin_id)."
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY"; 
    // $sql = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'total_sales'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //             mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //             AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //             AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //             AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //             AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //             AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //             AND mss_categories.category_type = " . $this->db->escape($data) . "
    //             AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //             AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //             AND mss_employees.employee_business_outlet= mss_business_outlets.business_outlet_id
    //             AND mss_business_admin.business_master_admin_id =" . $this->db->escape($ma_admin_id) . "
    //             AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastSixMonthServiceByType($data, $ma_admin_id){
    $sql ="SELECT sum(mss_transaction_services.txn_service_discounted_price)as total_service,
		        count(mss_transactions.txn_id) as service_count,
            DATE_FORMAT(mss_transactions.txn_datetime,'%M-%y') as 'date'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data)."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = ".$this->db->escape($ma_admin_id)."
            AND
            mss_transactions.txn_datetime BETWEEN  DATE_SUB(DATE_SUB(LAST_DAY(now()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY),INTERVAL 6 MONTH) 
              AND
               DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) -INTERVAL 1 DAY
            GROUP BY 
               month(mss_transactions.txn_datetime)
             ORDER BY
               year(mss_transactions.txn_datetime)DESC,month(mss_transactions.txn_datetime)DESC
             LIMIT 6";
    // $sql = "SELECT 
		// sum(mss_transaction_settlements.txn_settlement_amount_received) as total_service,
		// count(mss_transactions.txn_id) as service_count,
    //     DATE_FORMAT(mss_transactions.txn_datetime,'%M-%y') as 'date'
		// FROM
		// mss_transactions,
    //          mss_transaction_services,
    //          mss_employees,
    //          mss_customers,
    //          mss_categories,
    //          mss_sub_categories,
    //          mss_services,
    //          mss_business_outlets,
    //          mss_business_admin,
    //          mss_transaction_settlements
		// WHERE
		// mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
		// AND
		// mss_transactions.txn_customer_id = mss_customers.customer_id
		// AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(CURDATE(),INTERVAL 6 MONTH)
    //         AND
    //        		DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) -INTERVAL 1 DAY
		// AND mss_transactions.txn_status=1
		// AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
		// AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
    //     AND mss_business_admin.business_master_admin_id = " . $this->db->escape($ma_admin_id) . "
    //     AND mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
    //     AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //     AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //     AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //     AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //     AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //     AND mss_categories.category_type = " . $this->db->escape($data) . "
    //    	GROUP BY 
    //     	month(mss_transactions.txn_datetime)
    //     ORDER BY
    //     	year(mss_transactions.txn_datetime)DESC,month(mss_transactions.txn_datetime)DESC
    //     LIMIT 6";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastSixMonthPackage($data){
    $sql = "SELECT 
        SUM(mss_package_transaction_settlements.amount_received) AS package_sales,
        count(mss_package_transactions.package_txn_id) as 'package_count',
        DATE_FORMAT(mss_package_transactions.datetime,'%M-%y') as 'date'
      FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
                  mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
                  mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                  AND date(mss_package_transactions.datetime) BETWEEN  DATE_SUB(DATE_SUB(LAST_DAY(now()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY),INTERVAL 6 MONTH)  AND DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) -INTERVAL 1 DAY
                  AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                  AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                  AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
            GROUP BY 
                month(mss_package_transactions.datetime)
            ORDER BY
                year(mss_package_transactions.datetime)DESC,month(mss_package_transactions.datetime)DESC
                LIMIT 6";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetStateByMasterAdmin($data){
    $sql = "SELECT distinct(business_outlet_state) 
        from 
        mss_business_outlets ,
        mss_business_admin
        WHERE
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_admin.business_master_admin_id = " . $this->db->escape($data) . "
        AND
        mss_business_outlets.business_outlet_status = 1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCityByState($data){
    $sql = "SELECT DISTINCT(business_outlet_city) 
        from 
        mss_business_outlets ,
        mss_business_admin
        WHERE
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_admin.business_master_admin_id = " . $this->db->escape($data['business_master_admin_id']) . "
        AND
        mss_business_outlets.business_outlet_status = 1
        AND
        mss_business_outlets.business_outlet_state = " . $this->db->escape($data['state']) . ";";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletByCity($data){
    $sql = "SELECT business_outlet_name,business_outlet_id
            from 
            mss_business_outlets ,
            mss_business_admin
            WHERE
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = " . $this->db->escape($data['business_master_admin_id']) . "
            AND
            mss_business_outlets.business_outlet_status = 1
            AND
            mss_business_outlets.business_outlet_city = " . $this->db->escape($data['city']) . ";";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastFifteenDayServiceSalesByOutlet($data){
    $service ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'service_sales',
                date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'service_date'
                FROM
                mss_services,
                mss_business_outlets,
                mss_business_admin,
                mss_transactions,
                mss_transaction_settlements,
                mss_transaction_services,
                mss_customers
                WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND
                mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                AND
                mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND
                mss_services.service_type = 'service'
                AND
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                mss_business_outlets.business_outlet_id = ".$this->db->escape($data['outlet_id'])."
                AND
                mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                AND
                mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
                AND
                mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
              GROUP BY
                    date(mss_transactions.txn_datetime)
              ORDER BY
                    date(mss_transactions.txn_datetime)";
    // $service = "SELECT
    //         SUM(mss_transactions.txn_value) AS 'service_sales',
    //         date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'service_date'
    //         FROM
    //                 mss_transactions,
    //                 mss_transaction_services,
    //                 mss_employees,
    //                 mss_customers,
    //                 mss_categories,
    //                 mss_sub_categories,
    //                 mss_services,
    //                 mss_business_outlets,
    //                 mss_business_admin,
    //                 mss_transaction_settlements
    //         WHERE
    //            mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //            AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //            AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //            AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //            AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //            AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //            AND mss_categories.category_type = 'Service'
    //            AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //            AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //            AND mss_employees.employee_business_outlet= ".$this->db->escape($data['outlet_id'])."
    //            AND mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
    //            AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 15 day)
    //            GROUP BY
    //                 date(mss_transactions.txn_datetime)
    //            ORDER BY
    //                 date(mss_transactions.txn_datetime)
    //             LIMIT 15";
    $sql_service = $this->db->query($service);
    if ($sql_service->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_service->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastFifteenDayProdSalesByOutlet($data){
    $product ="SELECT sum(mss_transactions.txn_value) AS 'product_sales',
                date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'product_date'
                FROM
                mss_services,
                mss_business_outlets,
                mss_business_admin,
                mss_transactions,
                mss_transaction_settlements,
                mss_transaction_services,
                mss_customers
                WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND
                mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                AND
                mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND
                mss_services.service_type = 'otc'
                AND
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = ".$this->db->escape($data['outlet_id'])."
                AND
                mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                AND
                mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
                AND
                mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
               GROUP BY
                    date(mss_transactions.txn_datetime)
               ORDER BY
                    date(mss_transactions.txn_datetime)
                LIMIT 15";
    // $product = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'product_sales',
    //     date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'product_date'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //        mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //        AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //        AND mss_categories.category_type = 'Products'
    //        AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //        AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //        AND mss_employees.employee_business_outlet= " . $this->db->escape($data['outlet_id']) . "
    //        AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data['master_admin_id']) . "
    //        AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 15 day)
    //        GROUP BY
    //             date(mss_transactions.txn_datetime)
    //        ORDER BY
    //             date(mss_transactions.txn_datetime)
    //         LIMIT 15";
    $sql_products = $this->db->query($product);
    if ($sql_products->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_products->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
//   public function LastFifteenDayPackSalesByOutlet($data){
//     $package = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
//                     date_format(mss_package_transactions.datetime,'%Y-%m-%d') as 'package_date'
//         FROM
//             mss_package_transactions,
//             mss_package_transaction_settlements,
//             mss_customers,
//             mss_business_outlets,
//             mss_business_admin
//         WHERE
//             mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
//         AND
//             mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
//         AND
//             mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
//         AND
//             mss_business_admin.business_admin_id = mss_customers.customer_business_admin_id
//         AND
//             mss_business_admin.business_master_admin_id=" . $this->db->escape($data['master_admin_id']) . "
//         AND
//             mss_package_transactions.datetime >= date_sub(current_date,interval 15 day)
//         GROUP BY 
//             date(mss_package_transactions.datetime)
//         ORDER BY
//             date(mss_package_transactions.datetime)
//         LIMIT 15";
//     $sql_package = $this->db->query($package);
//     if ($sql_package->num_rows() > 0) {
//       return $this->ModelHelper(true, false, '', $sql_package->result_array());
//     } else {
//       return $this->ModelHelper(false, true, 'DB Error');
//     }
//   }
  public function GetOutletTodaySalesByType($data){
    $sql ="SELECT SUM(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
           count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_today_count'
           FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_id = ".$this->db->escape($data['outlet_id'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_transactions.txn_datetime >= CURRENT_DATE";
    // $sql = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'total_sales',
    //     sum(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_today_count'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //             mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //             AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //             AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //             AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //             AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //             AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //             AND mss_categories.category_type = ".$this->db->escape($data['type'])."
    //             AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //             AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //             AND mss_employees.employee_business_outlet= ".$this->db->escape($data['outlet_id'])."
    //             AND mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
    //             AND date(mss_transactions.txn_datetime) = CURRENT_DATE";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletTodayPackSales($data){
    $sql = "SELECT 
        SUM(mss_package_transaction_settlements.amount_received) AS package_sales,
        count(mss_package_transactions.package_txn_id) as 'pack_today_count'
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
        AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) = date(now())
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletTodayCountBySerAndProd($data){
    $sql = "SELECT 
        count(mss_transactions.txn_value) AS total_bill
        FROM
        mss_transactions,
        mss_transaction_settlements,
        mss_customers,
        mss_business_admin,
        mss_business_outlets
        WHERE
        mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) = date(now())
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletTodayCountByPack($data){
    $sql = "SELECT 
        count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) = date(now())
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCurrentBillCountByOutlet($data){
    $sql = "SELECT (sum(sales_bill)+sum(package_count)) AS total_bill
        FROM
        (SELECT 
        count(mss_transactions.txn_value) AS sales_bill
        FROM
        mss_transactions,
        mss_transaction_settlements,
        mss_customers,
        mss_business_admin,
        mss_business_outlets
        WHERE
        mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) >= DATE_FORMAT(now(),'%Y-%m-01')
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']) . ")sales_bill,
        (SELECT 
        count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) >= DATE_FORMAT(now(),'%Y-%m-01')
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']) . ")package_bill";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletPrevTillBillCount($data){
    $sql = "SELECT (sum(sales_bill)+sum(package_count)) AS total_bill
        FROM
        (SELECT 
        count(mss_transactions.txn_value) AS sales_bill
        FROM
        mss_transactions,
        mss_transaction_settlements,
        mss_customers,
        mss_business_admin,
        mss_business_outlets
        WHERE
        mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(now()-INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']) . ")sales_bill,
        (SELECT 
        count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(now()-INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']) . ")package_bill";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletPrevBillCount($data){
    $sql = "SELECT (sum(sales_bill)+sum(package_count)) AS total_bill
        FROM
        (SELECT 
        count(mss_transactions.txn_value) AS sales_bill
        FROM
        mss_transactions,
        mss_transaction_settlements,
        mss_customers,
        mss_business_admin,
        mss_business_outlets
        WHERE
        mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id 
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']) . ")sales_bill,
        (SELECT 
        count(mss_package_transaction_settlements.package_transaction_settlement_id) AS package_count
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_customers.customer_business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND mss_business_admin.business_master_admin_id = " . $this->db->escape($data['master_admin_id']) . ")package_bill";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletPreviousPackTillDate($data){
    $sql = "SELECT IFNULL(sum(mss_package_transaction_settlements.amount_received),0) as 'packages',
               count(mss_package_transaction_settlements.package_txn_id) as 'pack_prev_count_till'
                FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin
                    WHERE
                mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                AND
                mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                mss_business_outlets.business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
                AND
                mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                AND
                mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletPreviousPackSales($data){
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                count(mss_package_transaction_settlements.package_txn_id) as 'pack_prev_count'
                FROM
                    mss_package_transactions,
                    mss_package_transaction_settlements,
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                AND
                    mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND
                    mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                    mss_business_outlets.business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
                AND
                    mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                AND
                    mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CurrentOutletMonthPackSales($data){
    $sql = "SELECT IFNULL(sum(mss_package_transaction_settlements.amount_received),0) as 'packages',
        count(mss_package_transaction_settlements.package_txn_id) as 'pack_current_count'
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin
            WHERE
        mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
        AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_outlets.business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
        AND
        mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
        AND
        mss_package_transactions.datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetOutletCurrentMonthSalesByType($data){
    $sql = "SELECT IFNULL(sum(mss_transaction_services.txn_service_discounted_price),0) AS 'total_sales',
    count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_current_count'
    FROM
    mss_services,
    mss_business_outlets,
    mss_business_admin,
    mss_transactions,
    mss_transaction_settlements,
    mss_transaction_services,
    mss_customers
    WHERE
    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
    AND
    mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
    AND
    mss_transaction_services.txn_service_service_id = mss_services.service_id
    AND
    mss_services.service_type = ".$this->db->escape($data['type'])."
    AND
    mss_transactions.txn_customer_id = mss_customers.customer_id
    AND
    mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
    AND
    mss_business_outlets.business_outlet_id = ".$this->db->escape($data['outlet_id'])."
    AND
    mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
    AND
    mss_transactions.txn_datetime >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    // $sql = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'total_sales',
    //     count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_current_count'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //             mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //             AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //             AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //             AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //             AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //             AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //             AND mss_categories.category_type = ".$this->db->escape($data['type'])."
    //             AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //             AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //             AND mss_employees.employee_business_outlet= ".$this->db->escape($data['outlet_id'])."
    //             AND mss_business_admin.business_master_admin_id = ".$this->db->escape($data['master_admin_id']). "
    //             AND date(mss_transactions.txn_datetime) >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function OutletPreviousMonthTillSalesByType($data){
    $sql="SELECT IFNULL(sum(mss_transaction_services.txn_service_discounted_price),0) AS 'total_sales',
          count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count_till'
          FROM
          mss_services,
          mss_business_outlets,
          mss_business_admin,
          mss_transactions,
          mss_transaction_settlements,
          mss_transaction_services,
          mss_customers
          WHERE
          mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
          AND
          mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
          AND
          mss_transaction_services.txn_service_service_id = mss_services.service_id
          AND
          mss_services.service_type = ".$this->db->escape($data['type'])."
          AND
          mss_transactions.txn_customer_id = mss_customers.customer_id
          AND
          mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
          AND
          mss_business_outlets.business_outlet_id = ".$this->db->escape($data['outlet_id'])."
          AND
          mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
          AND
          mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    // $sql = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'total_sales',
    //     count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count_till'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //             mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //             AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //             AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //             AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //             AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //             AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //             AND mss_categories.category_type = ".$this->db->escape($data['type'])."
    //             AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //             AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //             AND mss_employees.employee_business_outlet= ".$this->db->escape($data['outlet_id'])."
    //             AND mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
    //             AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function OutletPreviousMonthSalesByType($data){
    $sql ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_id = ".$this->db->escape($data['outlet_id'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY";
    // $sql = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'total_sales',
    //     count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //             mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //             AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //             AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //             AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //             AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //             AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //             AND mss_categories.category_type = ".$this->db->escape($data['type'])."
    //             AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //             AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //             AND mss_employees.employee_business_outlet= ".$this->db->escape($data['outlet_id'])."
    //             AND mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
    //             AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetBusinessDetails($data)
  {
    $sql = "SELECT
           mss_business_admin.business_admin_id,mss_business_admin.business_admin_first_name,mss_business_outlets.business_outlet_id,mss_business_outlets.business_outlet_name
          FROM
             mss_business_outlets,
             mss_business_admin
          WHERE
             mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
          AND
             mss_business_admin.business_master_admin_id = ".$this->db->escape($data)."
          AND
             mss_business_outlets.business_outlet_status = 1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function FetchCategories($data)
  {
    
    $query = "SELECT  mss_categories.category_id,mss_categories.category_name,mss_categories.category_type,mss_business_outlets.business_outlet_name,mss_business_outlets.business_outlet_location,mss_business_admin.business_admin_first_name
      FROM 
        mss_categories,
        mss_business_outlets,
        mss_business_admin
      WHERE
        mss_categories.category_business_outlet_id = ".$data['category_business_outlet_id']."
      AND
        mss_categories.category_business_admin_id = ".$data['category_business_admin_id']."
      AND
        mss_categories.category_is_active = ".$data['category_is_active']."
      AND
        mss_business_outlets.business_outlet_id = ".$data['category_business_outlet_id']."
      AND
        mss_business_admin.business_admin_id = ".$data['category_business_admin_id']."";
      $sql = $this->db->query($query);
      if($sql->num_rows()>0)
      {
        return $this->ModelHelper(true,false,"",$sql->result_array());
        die;
      }
      else
      {
        return $this->ModelHelper(false,true,'Data Not found/Db error');
        die; 
      }
  }
  public function FetchSubCategories($data)
  {
    
    $query = "SELECT  mss_sub_categories.sub_category_id,mss_sub_categories.sub_category_name,mss_categories.category_id,mss_categories.category_name,mss_categories.category_type,mss_business_outlets.business_outlet_name,mss_business_outlets.business_outlet_location,mss_business_admin.business_admin_first_name
    FROM 
      mss_sub_categories,
      mss_categories,
      mss_business_outlets,
      mss_business_admin
    WHERE
      mss_sub_categories.sub_category_is_active = ".$data['category_is_active']."
    AND
      mss_categories.category_id = mss_sub_categories.sub_category_category_id
    AND
      mss_categories.category_business_outlet_id = ".$data['category_business_outlet_id']."
    AND
      mss_categories.category_business_admin_id = ".$data['category_business_admin_id']."
    AND
      mss_categories.category_is_active = ".$data['category_is_active']."
    AND
      mss_business_outlets.business_outlet_id = ".$data['category_business_outlet_id']."
    AND
      mss_business_admin.business_admin_id = ".$data['category_business_admin_id']."";
      $sql = $this->db->query($query);
      if($sql->num_rows()>0)
      {
        return $this->ModelHelper(true,false,"",$sql->result_array());
        die;
      }
      else
      {
        return $this->ModelHelper(false,true,'Data Not found/Db error');
        die; 
      }
  }
   //22-04-2020
  public function GetCityTodaySalesByType($data){
    $sql = "SELECT SUM(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_today_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_city = ".$this->db->escape($data['city'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime >= CURRENT_DATE";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCityCurrentMonthSalesByType($data){
    $sql = "SELECT IFNULL(sum(mss_transaction_services.txn_service_discounted_price),0) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_current_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_city = ".$this->db->escape($data['city'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CityPreviousMonthTillSalesByType($data){
    $sql="SELECT IFNULL(sum(mss_transaction_services.txn_service_discounted_price),0) AS 'total_sales',
          count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count_till'
          FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_city = ".$this->db->escape($data['city'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
        $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CityPreviousMonthSalesByType($data){
    $sql ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_city = ".$this->db->escape($data['city'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCityTodayPackSales($data){
    $sql = "SELECT 
    SUM(mss_package_transaction_settlements.amount_received) AS package_sales,
    count(mss_package_transactions.package_txn_id) as 'pack_today_count'
    FROM
    mss_package_transactions,
    mss_package_transaction_settlements,
    mss_customers,
    mss_business_outlets,
    mss_business_admin,
    mss_master_admin
    WHERE
    mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
    AND date(mss_package_transactions.datetime) = date(now())
    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
    AND mss_business_outlets.business_outlet_city= ".$this->db->escape($data['city'])."
    AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
    AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
    AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id']);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCityPreviousPackTillDate($data){
    $sql = "SELECT IFNULL(sum(mss_package_transaction_settlements.amount_received),0) as 'packages',
               count(mss_package_transaction_settlements.package_txn_id) as 'pack_prev_count_till'
               FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin,
                mss_master_admin
                WHERE
                mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
                AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND mss_business_outlets.business_outlet_city= ".$this->db->escape($data['city'])."
                AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
                AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
                AND
                mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetCityPreviousPackSales($data){
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                count(mss_package_transaction_settlements.package_txn_id) as 'pack_prev_count'
                FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin,
                mss_master_admin
                WHERE
                mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
                AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND date(mss_package_transactions.datetime) = date(now())
                AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND mss_business_outlets.business_outlet_city= ".$this->db->escape($data['city'])."
                AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
                AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
                AND
                    mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CurrentCityMonthPackSales($data){
    $sql = "SELECT IFNULL(sum(mss_package_transaction_settlements.amount_received),0) as 'packages',
        count(mss_package_transaction_settlements.package_txn_id) as 'pack_current_count'
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin,
        mss_master_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
        AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) = date(now())
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_outlets.business_outlet_city= ".$this->db->escape($data['city'])."
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
        AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
        AND
        mss_package_transactions.datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetStateTodaySalesByType($data){
    $sql = "SELECT SUM(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_today_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_state = ".$this->db->escape($data['state'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime >= CURRENT_DATE";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetStateCurrentMonthSalesByType($data){
    $sql = "SELECT IFNULL(sum(mss_transaction_services.txn_service_discounted_price),0) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_current_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_state = ".$this->db->escape($data['state'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime >= DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function StatePreviousMonthTillSalesByType($data){
    $sql="SELECT IFNULL(sum(mss_transaction_services.txn_service_discounted_price),0) AS 'total_sales',
          count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count_till'
          FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_state = ".$this->db->escape($data['state'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
        $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function StatePreviousMonthSalesByType($data){
    $sql ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
            count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_prev_count'
            FROM
            mss_services,
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_transaction_settlements,
            mss_transaction_services,
            mss_customers,
            mss_master_admin
            WHERE
            mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
            AND
            mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
            AND
            mss_transaction_services.txn_service_service_id = mss_services.service_id
            AND
            mss_services.service_type = ".$this->db->escape($data['type'])."
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_state = ".$this->db->escape($data['state'])."
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
            AND
            mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
            AND
            mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND date_sub(last_day(CURRENT_DATE),INTERVAL DAY(last_day(CURRENT_DATE))-1 DAY)-INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetStateTodayPackSales($data){
    $sql = "SELECT 
    SUM(mss_package_transaction_settlements.amount_received) AS package_sales,
    count(mss_package_transactions.package_txn_id) as 'pack_today_count'
    FROM
    mss_package_transactions,
    mss_package_transaction_settlements,
    mss_customers,
    mss_business_outlets,
    mss_business_admin,
    mss_master_admin
    WHERE
    mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
    AND date(mss_package_transactions.datetime) = date(now())
    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
    AND mss_business_outlets.business_outlet_state= ".$this->db->escape($data['state'])."
    AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
    AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
    AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id']);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetStatePreviousPackTillDate($data){
    $sql = "SELECT IFNULL(sum(mss_package_transaction_settlements.amount_received),0) as 'packages',
               count(mss_package_transaction_settlements.package_txn_id) as 'pack_prev_count_till'
               FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin,
                mss_master_admin
                WHERE
                mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
                AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND mss_business_outlets.business_outlet_state= ".$this->db->escape($data['state'])."
                AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
                AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
                AND
                mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE(now()-INTERVAL 1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function GetStatePreviousPackSales($data){
    $sql = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                count(mss_package_transaction_settlements.package_txn_id) as 'pack_prev_count'
                FROM
                mss_package_transactions,
                mss_package_transaction_settlements,
                mss_customers,
                mss_business_outlets,
                mss_business_admin,
                mss_master_admin
                WHERE
                mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
                AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND date(mss_package_transactions.datetime) = date(now())
                AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND mss_business_outlets.business_outlet_state= ".$this->db->escape($data['state'])."
                AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
                AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
                AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
                AND
                    mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND DATE_SUB(LAST_DAY(CURDATE()),INTERVAL DAY(LAST_DAY(CURDATE()))- 1 DAY)- INTERVAL 1 DAY";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CurrentStateMonthPackSales($data){
    $sql = "SELECT IFNULL(sum(mss_package_transaction_settlements.amount_received),0) as 'packages',
        count(mss_package_transaction_settlements.package_txn_id) as 'pack_current_count'
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers,
        mss_business_outlets,
        mss_business_admin,
        mss_master_admin
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id 
        AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND date(mss_package_transactions.datetime) = date(now())
        AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND mss_business_outlets.business_outlet_state= ".$this->db->escape($data['state'])."
        AND mss_customers.customer_business_admin_id = mss_business_admin.business_admin_id
        AND mss_business_admin.business_master_admin_id = mss_master_admin.master_admin_id
        AND mss_master_admin.master_admin_id = ".$this->db->escape($data['master_admin_id'])."
        AND
        mss_package_transactions.datetime >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $query->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function OutletLastFifteenDaySalesByType($data){
    $service ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
                date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'bill_date',
                count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_count'
                FROM
                mss_services,
                mss_business_outlets,
                mss_business_admin,
                mss_transactions,
                mss_transaction_settlements,
                mss_transaction_services,
                mss_customers
                WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND
                mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                AND
                mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND
                mss_services.service_type = ".$this->db->escape($data['type'])."
                AND
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                mss_business_outlets.business_outlet_id = ".$this->db->escape($data['outlet_id'])."
                AND
                mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                AND
                mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
                AND
                mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
              GROUP BY
                    date(mss_transactions.txn_datetime)
              ORDER BY
                    date(mss_transactions.txn_datetime)";
    // $service = "SELECT
    //         SUM(mss_transactions.txn_value) AS 'service_sales',
    //         date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'service_date'
    //         FROM
    //                 mss_transactions,
    //                 mss_transaction_services,
    //                 mss_employees,
    //                 mss_customers,
    //                 mss_categories,
    //                 mss_sub_categories,
    //                 mss_services,
    //                 mss_business_outlets,
    //                 mss_business_admin,
    //                 mss_transaction_settlements
    //         WHERE
    //            mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //            AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //            AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //            AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //            AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //            AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //            AND mss_categories.category_type = 'Service'
    //            AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //            AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //            AND mss_employees.employee_business_outlet= ".$this->db->escape($data['outlet_id'])."
    //            AND mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
    //            AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 15 day)
    //            GROUP BY
    //                 date(mss_transactions.txn_datetime)
    //            ORDER BY
    //                 date(mss_transactions.txn_datetime)
    //             LIMIT 15";
    $sql_service = $this->db->query($service);
    if ($sql_service->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_service->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function CityLastFifteenDaySalesByType($data){
    $product ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
                date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'bill_date',
                count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_count'
                FROM
                mss_services,
                mss_business_outlets,
                mss_business_admin,
                mss_transactions,
                mss_transaction_settlements,
                mss_transaction_services,
                mss_customers
                WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND
                mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                AND
                mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND
                mss_services.service_type = ".$this->db->escape($data['type'])."
                AND
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                mss_business_outlets.business_outlet_city= ".$this->db->escape($data['city'])."
                AND
                mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                AND
                mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
                AND
                mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
              GROUP BY
                    date(mss_transactions.txn_datetime)
              ORDER BY
                    date(mss_transactions.txn_datetime)";
    // $product = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'product_sales',
    //     date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'product_date'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //        mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //        AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //        AND mss_categories.category_type = 'Products'
    //        AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //        AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //        AND mss_employees.employee_business_outlet= " . $this->db->escape($data['outlet_id']) . "
    //        AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data['master_admin_id']) . "
    //        AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 15 day)
    //        GROUP BY
    //             date(mss_transactions.txn_datetime)
    //        ORDER BY
    //             date(mss_transactions.txn_datetime)
    //         LIMIT 15";
    $sql_products = $this->db->query($product);
    if ($sql_products->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_products->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function StateLastFifteenDaySalesByType($data){
    $product ="SELECT sum(mss_transaction_services.txn_service_discounted_price) AS 'total_sales',
                date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'bill_date',
                count(mss_transaction_settlements.txn_settlement_txn_id) as 'bill_count'
                FROM
                mss_services,
                mss_business_outlets,
                mss_business_admin,
                mss_transactions,
                mss_transaction_settlements,
                mss_transaction_services,
                mss_customers
                WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND
                mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                AND
                mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND
                mss_services.service_type = ".$this->db->escape($data['type'])."
                AND
                mss_transactions.txn_customer_id = mss_customers.customer_id
                AND
                mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                mss_business_outlets.business_outlet_state = ".$this->db->escape($data['state'])."
                AND
                mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                AND
                mss_business_admin.business_master_admin_id =".$this->db->escape($data['master_admin_id'])."
                AND
                mss_transactions.txn_datetime >= date_sub(current_date,interval 15 day)
              GROUP BY
                    date(mss_transactions.txn_datetime)
              ORDER BY
                    date(mss_transactions.txn_datetime)";
    // $product = "SELECT
    //     SUM(mss_transactions.txn_value) AS 'product_sales',
    //     date_format(mss_transactions.txn_datetime,'%Y-%m-%d') as 'product_date'
    //     FROM
    //             mss_transactions,
    //             mss_transaction_services,
    //             mss_employees,
    //             mss_customers,
    //             mss_categories,
    //             mss_sub_categories,
    //             mss_services,
    //             mss_business_outlets,
    //             mss_business_admin,
    //             mss_transaction_settlements
    //     WHERE
    //        mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
    //        AND mss_transaction_settlements.txn_settlement_txn_id = mss_transactions.txn_id
    //        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    //        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    //        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    //        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    //        AND mss_categories.category_type = 'Products'
    //        AND mss_transactions.txn_customer_id = mss_customers.customer_id
    //        AND mss_employees.employee_business_admin=mss_business_admin.business_admin_id
    //        AND mss_employees.employee_business_outlet= " . $this->db->escape($data['outlet_id']) . "
    //        AND mss_business_admin.business_master_admin_id =" . $this->db->escape($data['master_admin_id']) . "
    //        AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 15 day)
    //        GROUP BY
    //             date(mss_transactions.txn_datetime)
    //        ORDER BY
    //             date(mss_transactions.txn_datetime)
    //         LIMIT 15";
    $sql_products = $this->db->query($product);
    if ($sql_products->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_products->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastFifteenDayPackSalesByOutlet($data){
    $package = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                    date_format(mss_package_transactions.datetime,'%Y-%m-%d') as 'pack_date',
                    count(mss_package_transaction_settlements.package_transaction_settlement_id) AS 'package_count'
                FROM
                    mss_package_transactions,
                    mss_package_transaction_settlements,
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                AND
                    mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                AND
                    mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                AND
                    mss_business_outlets.business_outlet_id = " . $this->db->escape($data['outlet_id']) . "
                AND
                    mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                AND
                    mss_business_admin.business_master_admin_id=" . $this->db->escape($data['master_admin_id']) . "
                AND
                    mss_package_transactions.datetime >= date_sub(current_date,interval 15 day)
                GROUP BY 
                    date(mss_package_transactions.datetime)
                ORDER BY
                    date(mss_package_transactions.datetime)
                LIMIT 15";
    $sql_package = $this->db->query($package);
    if ($sql_package->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_package->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastFifteenDayPackSalesByCity($data){
    $package = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                    date_format(mss_package_transactions.datetime,'%Y-%m-%d') as 'pack_date',
                    count(mss_package_transaction_settlements.package_transaction_settlement_id) AS 'package_count'
                    FROM
                        mss_package_transactions,
                        mss_package_transaction_settlements,
                        mss_customers,
                        mss_business_outlets,
                        mss_business_admin
                    WHERE
                        mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                    AND
                        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                    AND
                        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND
                    mss_business_outlets.business_outlet_city = " . $this->db->escape($data['city']) . "
                    AND
                        mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND
                        mss_business_admin.business_master_admin_id=" . $this->db->escape($data['master_admin_id']) . "
                    AND
                        mss_package_transactions.datetime >= date_sub(current_date,interval 15 day)
                    GROUP BY 
                        date(mss_package_transactions.datetime)
                    ORDER BY
                        date(mss_package_transactions.datetime)
                    LIMIT 15";
    $sql_package = $this->db->query($package);
    if ($sql_package->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_package->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  public function LastFifteenDayPackSalesByState($data){
    $package = "SELECT sum(mss_package_transaction_settlements.amount_received) as 'packages',
                    date_format(mss_package_transactions.datetime,'%Y-%m-%d') as 'pack_date',
                    count(mss_package_transaction_settlements.package_transaction_settlement_id) AS 'package_count'
                    FROM
                        mss_package_transactions,
                        mss_package_transaction_settlements,
                        mss_customers,
                        mss_business_outlets,
                        mss_business_admin
                    WHERE
                        mss_package_transaction_settlements.package_txn_id = mss_package_transactions.package_txn_id
                    AND
                        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                    AND
                        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND
                        mss_business_outlets.business_outlet_state = ".$this->db->escape($data['state'])."
                    AND
                        mss_business_admin.business_admin_id = mss_business_outlets.business_outlet_business_admin
                    AND
                        mss_business_admin.business_master_admin_id=" . $this->db->escape($data['master_admin_id']) . "
                    AND
                        mss_package_transactions.datetime >= date_sub(current_date,interval 15 day)
                    GROUP BY 
                        date(mss_package_transactions.datetime)
                    ORDER BY
                        date(mss_package_transactions.datetime)
                    LIMIT 15";
    $sql_package = $this->db->query($package);
    if ($sql_package->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql_package->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
  //19-05-2020
  public function ServicesExport($data){
    $query = "SELECT mss_services.service_name,
              mss_services.inventory_type,
              mss_services.service_price_inr,
              mss_services.service_is_active,
              mss_services.service_est_time,
              mss_services.service_description,
              mss_services.service_gst_percentage,
              mss_services.service_type,
              mss_services.barcode,
              -- mss_services.barcode_id,
              mss_services.service_unit,
              mss_services.service_brand,
              mss_services.qty_per_item,
              mss_business_outlets.business_outlet_name,
              mss_business_admin.business_admin_first_name
              FROM 
              mss_services,
              mss_sub_categories,
              mss_categories,
              mss_business_admin,
              mss_business_outlets
              WHERE
              mss_services.service_type = ".$this->db->escape($data['service_type'])."
              AND
              mss_services.service_is_active = 1
              AND
              mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
              AND
              mss_sub_categories.sub_category_category_id = mss_categories.category_id
              AND
              mss_categories.category_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_id = ".$this->db->escape($data['category_business_outlet_id'])."
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_admin.business_admin_id = ".$this->db->escape($data['category_business_admin_id'])."
    ";
    $sql = $this->db->query($query);
    if ($sql->num_rows() > 0) {
      return $this->ModelHelper(true, false, '', $sql->result_array());
    } else {
      return $this->ModelHelper(false, true, 'DB Error');
    }
  }
}

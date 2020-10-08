<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BusinessAdminModel extends CI_Model {
	

	public function ModelHelper($success,$error,$error_msg = '',$data_arr = array()){
        if($success == true && $error == false){
            $data = array(
                'success' => 'true',
                'error'   => 'false',
                'message' => $error_msg,
                'res_arr' => $data_arr 
            );
            
            return $data;
        }
        elseif ($success == false && $error == true) {
            $data = array(
                'success' => 'false',
                'error'   => 'true',
                'message' => $error_msg
            );
            
            return $data;
        }
    }
	 //Testing Function
	 private function PrintArray($data){
		print("<pre>".print_r($data,true)."</pre>");
		die;
	}
    //public function for logging in the business-admin to dashboard    
    public function BusinessAdminLogin($data) {
        $this->db->select('*');
        $this->db->from('mss_business_admin');
        $this->db->where('business_admin_email',$data['business_admin_email']);
        $this->db->limit(1);
        
        $query = $this->db->get();

        if ($query->num_rows() == 1){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'No such business admin exists!');
        }
    }
    

    public function BusinessAdminByEmail($email) {

        $this->db->select('*');
        $this->db->from('mss_business_admin');
        $this->db->where('business_admin_email',$email);
        $this->db->limit(1);
        
        //execute the query
        $query = $this->db->get();

        if ($query->num_rows() == 1){
            return $this->ModelHelper(true,false,'',$query->row_array());
        } 
        else{
            return $this->ModelHelper(false,true,"Duplicate emails are there!");
        }
    }


    //Generic function which will give all details by primary key of table
    public function DetailsById($id,$table_name,$where)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where,$id);
        $this->db->limit(1);
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() == 1){
           return $this->ModelHelper(true,false,'',$query->row_array());
        } 
        else{
           return $this->ModelHelper(false,true,"Duplicate rows found!");
        }
    }

    //Generic function
    public function MultiWhereSelect($table_name,$where_array){
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where_array);
        
        //execute the query
        $query = $this->db->get();

        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    //Generic function
    public function FullTable($table_name){
        $query = $this->db->get($table_name);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    //Generic function
    public function Update($data,$table_name,$where){
        $this->db->where($where, $data[$where]);
        $this->db->update($table_name, $data);
        if($this->db->affected_rows() > 0){
            return $this->ModelHelper(true,false);    
        }
        else{
            return $this->ModelHelper(false,true,"No row updated!");
        }    
        
    }


    //Generic function
    public function Insert($data,$table_name){
			$result=$this->db->insert($table_name,$data);
        if($result){
            $data = array('insert_id' => $this->db->insert_id());
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"Check your inserted query!",$data);
        }
    }

    public function IsBeingUsed($table_name,$data,$where){
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where,$data);
        
        $query = $this->db->get();

        if ($query->num_rows() >= 1){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'No Row currently in use!');
        }
    }

    public function BusinessAdminPackages($business_admin_id){

        $sql = "SELECT A.business_admin_package_id,A.business_admin_id,B.package_id,B.package_name,A.package_expiry_date FROM mss_business_admin_packages AS A,mss_packages AS B WHERE A.business_admin_id = ".$this->db->escape($business_admin_id)." AND A.package_id = B.package_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function SubCategories($where){
       $sql = "SELECT sub_category_id,sub_category_category_id,sub_category_name,sub_category_is_active,sub_category_description,category_name,category_type FROM mss_sub_categories AS A,mss_categories AS B WHERE A.sub_category_category_id = B.category_id AND B.category_business_admin_id = ".$this->db->escape($where['category_business_admin_id'])." AND sub_category_is_active = ".$this->db->escape($where['sub_category_is_active'])." AND B.category_business_outlet_id=".$this->db->escape($where['category_business_outlet_id'])."";
        
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function Services($where){
       $sql = "SELECT * FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND A.category_business_admin_id = ".$this->db->escape($where['category_business_admin_id'])." AND C.service_is_active = ".$this->db->escape($where['service_is_active'])." AND A.category_business_outlet_id = ".$this->db->escape($where['category_business_outlet_id'])." AND C.service_type = ".$this->db->escape($where['service_type'])."";
        
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    
    public function DeactiveCategory($category_id){   
            $sql1="update mss_services,mss_sub_categories set mss_services.service_is_active = 0 
            where mss_services.service_sub_category_id=mss_sub_categories.sub_category_id
            AND mss_sub_categories.sub_category_category_id =".$this->db->escape($category_id)."";
            $sql2="update mss_sub_categories,mss_categories set mss_sub_categories.sub_category_is_active = 0 
            where mss_sub_categories.sub_category_category_id=".$this->db->escape($category_id)."";  
            $sql = "update mss_categories set mss_categories.category_is_active = 0
            where mss_categories.category_id = ".$this->db->escape($category_id).""; 
                $query = $this->db->query($sql);
                $query1 = $this->db->query($sql1);
                $query2 = $this->db->query($sql2);
                if($this->db->affected_rows() > 0){      
                    return $this->ModelHelper(true,false);       
                }else{                       
                    return $this->ModelHelper(false,true,"No row updated!");                
                }    
    }
	 
		 

			public function DeactiveSubCategory($sub_category_id){    
				$sql = "UPDATE mss_sub_categories,mss_services 							 
				SET mss_sub_categories.sub_category_is_active = FALSE,							 
				mss_services.service_is_active = FALSE								
				WHERE mss_sub_categories.sub_category_id = ".$this->db->escape($sub_category_id)." AND 				
				mss_services.service_sub_category_id = mss_sub_categories.sub_category_id";
				
				$query = $this->db->query($sql);			
				if($this->db->affected_rows() > 0){				
						return $this->ModelHelper(true,false);		
					}else{						 
			 			return $this->ModelHelper(false,true,"No row updated!");
					}			 
		 }

    public function ViewCompositionBasic($where){
        $sql = "SELECT * FROM mss_raw_composition,mss_services,mss_sub_categories,mss_categories WHERE mss_raw_composition.service_id = mss_services.service_id AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id AND mss_sub_categories.sub_category_category_id = mss_categories.category_id AND mss_categories.category_business_admin_id = ".$this->db->escape($where['business_admin_id'])." AND mss_categories.category_business_outlet_id =".$this->db->escape($where['business_outlet_id'])." GROUP BY mss_raw_composition.service_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function ViewComposition($where){
        $sql = "SELECT 
						s2.service_name AS 'service',
						s1.service_name AS 'product_name',
						s1.service_unit,
						s1.service_brand,
						mss_raw_composition.consumption_quantity
						
				FROM
					mss_services s1
					LEFT JOIN 
					mss_raw_composition ON
					s1.service_id= mss_raw_composition.rmc_id
					LEFT JOIN mss_services s2 ON
					s2.service_id= mss_raw_composition.service_id
				WHERE 
					s1.service_id= mss_raw_composition.rmc_id AND
					s2.service_id= mss_raw_composition.service_id AND
					s2.service_id= ".$this->db->escape($where['service_id'])." ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function DeleteMultiple($table_name,$data,$where){
        $this->db->where($where, $data);
        $this->db->delete($table_name);

        if (!$this->db->affected_rows()) {
            $result = 'Error! ID ['.$data.'] not found';
            return $this->ModelHelper(false,true,$result);
        } 
        else{
            return $this->ModelHelper(true,false);
        }
    }

    public function DeleteRawMaterialCategory($data){
        
        $this->db->trans_start();

        $this->db->where('rmc_id', $data['raw_material_category_id']);
        $this->db->delete('mss_raw_material_stock');

        $this->db->where('raw_material_category_id', $data['raw_material_category_id']);
        $this->db->update('mss_raw_material_categories',$data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return $this->ModelHelper(false,true,'Cannot be processed!');
        }
        
        return $this->ModelHelper(true,false);
    }
    
    public function AddServiceSubCategoryBulkPackage($data,$sub_categories,$count){
        
        $result = $this->Insert($data,'mss_salon_packages');

        $last_insert_id = $result['res_arr']['insert_id'];

        //create a services packages
        for($i=0;$i < count($sub_categories);$i++){

            //for each sub category id -> add all services in it
            $services_data = $this->MultiWhereSelect('mss_services',array('service_sub_category_id' => $sub_categories[$i]));
            
            $services = $services_data['res_arr'];
            foreach ($services as $service) {
                $data_2 = array(
                    'salon_package_id' => $last_insert_id,
                    'service_id' => $service['service_id'],
                    'discount_percentage' => 100,
                    'service_count' => (int)$count[$i]
                );
                $result_2 = $this->Insert($data_2,'mss_salon_package_data');
            }
        }

        return $this->ModelHelper(true,false);
    }

    public function AddDiscountSubCategoryBulkPackage($data,$sub_categories,$discounts,$count){
        $result = $this->Insert($data,'mss_salon_packages');

        $last_insert_id = $result['res_arr']['insert_id'];

        //create a discounts packages
        for($i=0;$i<count($sub_categories);$i++){

            //for each sub category id -> add all services in it
            $services_data = $this->MultiWhereSelect('mss_services',array('service_sub_category_id' => (int)$sub_categories[$i]));
            
            $services = $services_data['res_arr'];
            foreach ($services as $service) {
                $data_2 = array(
                    'salon_package_id' => $last_insert_id,
                    'service_id' => $service['service_id'],
                    'discount_percentage' => (int)$discounts[$i],
                    'service_count' => (int)$count[$i]
                );
                $result_2 = $this->Insert($data_2,'mss_salon_package_data');
            }
        }
        
        return $this->ModelHelper(true,false);
    }
    
    // Add category packages for salon
    public function AddServiceCategoryBulkPackage($data,$categories,$count){   

      $result = $this->Insert($data,'mss_salon_packages');
      $last_insert_id = $result['res_arr']['insert_id'];

      //create a services packages
      for($i=0;$i < count($categories);$i++){
					$sub_categories=$this->MultiWhereSelect('mss_sub_categories',array('sub_category_category_id' => $categories[$i]));
					$sub_categories=$sub_categories['res_arr'];
					for($j=0;$j< count($sub_categories);$j++){
          //for each sub category id -> add all services in it
						$services_data = $this->MultiWhereSelect('mss_services',array('service_sub_category_id' => $sub_categories[$i]['sub_category_id']));
						
						$services = $services_data['res_arr'];
						foreach ($services as $service) {
								$data_2 = array(
										'salon_package_id' => $last_insert_id,
										'service_id' => $service['service_id'],
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

        $result = $this->Insert($data,'mss_salon_packages');

        $last_insert_id = $result['res_arr']['insert_id'];

        //create a discounts packages
        for($i=0;$i<count($categories);$i++){
					$sub_categories=$this->MultiWhereSelect('mss_sub_categories',array('sub_category_category_id' => $categories[$i]));
					$sub_categories=$sub_categories['res_arr'];
					for($j=0;$j< count($sub_categories);$j++){
					//for each sub category id -> add all services in it
						$services_data = $this->MultiWhereSelect('mss_services',array('service_sub_category_id' => $sub_categories[$j]['sub_category_id']));
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
    // end
    
    //ServicePAckage
		public function AddDiscountServicePackage($post,$data,$count,$where){
        // $this->PrintArray($_POST['category_type1']);
		$result = $this->Insert($data,'mss_salon_packages');
		$last_insert_id = $result['res_arr']['insert_id'];	
		
        if(!empty($_POST['category_type1'])){
            for($i=0;$i<count($_POST['category_type1']);$i++){
                $filter=array(
                    'category_type'=>$_POST['category_type1'][$i],
                    'min_price'=>$_POST['min_price1'][$i],
                    'max_price'=>$_POST['max_price1'][$i],
                    'business_admin_id'=>$where['business_admin_id'],
                    'business_outlet_id'=>$where['business_outlet_id']
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
    // end
    //ServicesBY price
	public function ServiceByPrice($data){
		// $this->PrintArray($data);
        $sql="SELECT 
					mss_services.service_id
				FROM 
					mss_services,
					mss_sub_categories,
					mss_categories
				WHERE
					mss_services.service_type=".$this->db->escape($data['category_type'])." AND
					mss_services.service_sub_category_id= mss_sub_categories.sub_category_id AND
					mss_sub_categories.sub_category_category_id= mss_categories.category_id AND
					mss_services.service_price_inr >= ".$this->db->escape($data['service_price_inr'])." AND
					mss_categories.category_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND 
					mss_categories.category_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    
    public function ServiceBetweenPrice($data){
		// $this->PrintArray($data);
        $sql="SELECT 
					mss_services.service_id
				FROM 
					mss_services,
					mss_sub_categories,
					mss_categories
				WHERE
					mss_services.service_type=".$this->db->escape($data['category_type'])." AND
					mss_services.service_sub_category_id= mss_sub_categories.sub_category_id AND
					mss_sub_categories.sub_category_category_id= mss_categories.category_id AND
					mss_services.service_price_inr BETWEEN ".$this->db->escape($data['min_price'])." AND ".$this->db->escape($data['max_price'])." AND 
					mss_categories.category_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND 
					mss_categories.category_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }

    public function ServiceBetweenPrice2($data){
		  // $this->PrintArray($data);
        $sql="SELECT DISTINCT
					mss_services.service_id
				FROM 
					mss_services,
					mss_sub_categories,
					mss_categories
				WHERE
					mss_services.service_sub_category_id= mss_sub_categories.sub_category_id AND
					mss_sub_categories.sub_category_category_id=  ".$this->db->escape($data['category_id'])." AND
					mss_services.service_price_inr BETWEEN ".$this->db->escape($data['min_price'])." AND ".$this->db->escape($data['max_price'])." AND 
					mss_categories.category_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND 
					mss_categories.category_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }

    public function ServiceBetweenPrice3($data){
		  // $this->PrintArray($data);
        $sql="SELECT DISTINCT
					mss_services.service_id
				FROM 
					mss_services,
					mss_sub_categories,
					mss_categories
				WHERE
					mss_services.service_sub_category_id= ".$this->db->escape($data['sub_category_id'])." AND
					mss_sub_categories.sub_category_category_id= mss_categories.category_id AND
					mss_services.service_price_inr BETWEEN ".$this->db->escape($data['min_price'])." AND ".$this->db->escape($data['max_price'])." AND 
					mss_categories.category_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND 
					mss_categories.category_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    
    public function ServiceBetweenPrice4($data){
		  // $this->PrintArray($data);
        $sql="SELECT 
					mss_services.service_id
				FROM 
					mss_services
				WHERE
					mss_services.service_type=".$this->db->escape($data['category_type'])." AND 
					mss_services.service_id= ".$this->db->escape($data['service_id'])." ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }    
    public function AddServicePackageForSalon($data,$services,$count_service){		    
				$result = $this->Insert($data,'mss_salon_packages');
        $last_insert_id = $result['res_arr']['insert_id'];
        $count = 0;
        //create a services packages
        for($i=0;$i<count($services);$i++){
            $data_2 = array(
                'salon_package_id' => $last_insert_id,
                'service_id' => (int)$services[$i],
                'discount_percentage' => 100,
                'service_count' => (int)$count_service[$i]
            );
            $result_2 = $this->Insert($data_2,'mss_salon_package_data');
            
            if($result_2['success'] == 'true'){
                $count = $count + 1;
            }
           
        }
        if($count  == count($services)){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,"Cannot Process!");        
        }
    }

    public function AddDiscountPackageForSalon($data,$services,$discounts,$count_discount){
        $result = $this->Insert($data,'mss_salon_packages');

        $last_insert_id = $result['res_arr']['insert_id'];

        $count = 0;
        //create a discounts packages
        for($i=0;$i < count($services);$i++){
           
            $data_2 = array(
                'salon_package_id' => $last_insert_id,
                'service_id' => (int)$services[$i],
                'discount_percentage' => (int)$discounts[$i],
                'service_count' => (int)$count_discount[$i]
            );
            $result_2 = $this->Insert($data_2,'mss_salon_package_data');
            
            if($result_2['success'] == 'true'){
                $count = $count + 1;
            }    
        }
        
        
        if($count  == count($services)){
            return $this->ModelHelper(true,false);
        }
        elseif($count == 0){
            return $this->ModelHelper(false,true,"Cannot Process!");        
        }
        else{
            return $this->ModelHelper(true,false,"Parially Processed,Consider deactivating package!");        
        }
    }

    private function CheckIfPackageServiceExists($insert_id,$service_id){
        $this->db->select('*');
        $this->db->from('mss_salon_package_data');
        $this->db->where('salon_package_id',$insert_id);
        $this->db->where('service_id',$service_id);
        
        $query = $this->db->get();

        if($query->num_rows() === 1){
            return $this->ModelHelper(true,false);
        }
        else if($query->num_rows() === 0){
            return $this->ModelHelper(false,true);
        }   
    }

    public function GetAllPackages($where){
        $sql = "SELECT * FROM mss_salon_packages WHERE business_admin_id = ".$this->db->escape($where['business_admin_id'])." AND business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetOverAllBillWiseSalesReport($data){
        $data['result'] = [];
        $sql = "SELECT 
        mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
        date(mss_transactions.txn_datetime) AS 'Billing Date',
        mss_customers.customer_mobile AS 'Mobile No',
        mss_customers.customer_name AS 'Customer Name',
        sum((txn_service_discounted_price+txn_add_on_amount)*txn_service_quantity) AS 'MRP Amt',
        -- (mss_transactions.txn_discount+mss_transactions.txn_value) AS 'MRP Amt',
        mss_transactions.txn_discount AS 'Discount',
        (sum((txn_service_discounted_price+txn_add_on_amount)*txn_service_quantity)-mss_transactions.txn_discount) AS 'Net Amount',
        -- mss_transactions.txn_value AS 'Net Amount',
        -- mss_transactions.txn_status AS 'billed=1/canceled=0',
        -- mss_transactions.txn_remarks AS 'Remarks',
        mss_transactions.txn_total_tax AS 'Total Tax (Rs.)',
        mss_transactions.txn_pending_amount AS 'Pending Amount',
        mss_transaction_settlements.txn_settlement_way AS 'Settlement Way',
        mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode'
        
    FROM 
        mss_customers,
        mss_transactions,
        mss_transaction_settlements,
        mss_employees,
        mss_transaction_services
    WHERE 
        mss_transaction_services.txn_service_txn_id = mss_transactions.txn_id
        AND mss_transactions.txn_customer_id = mss_customers.customer_id
        AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
        AND mss_transactions.txn_cashier= mss_employees.employee_id
        AND mss_transactions.txn_status=1
        AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
        AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])." 
        AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])." GROUP BY mss_transactions.txn_id ";


        $query = $this->db->query($sql);

        if($query){
            $result = $query->result_array();            
            $result_to_send = array();

            for($i=0;$i<count($result);$i++){                
                $result[$i]["Service Type"] =  "Service";
                array_push($result_to_send, $result[$i]);
                if($result[$i]["Settlement Way"] == "Split Payment" && $result[$i]["Payment Mode"] != "Split"){
                    $str = $result[$i]["Payment Mode"];
                    $someArray = json_decode($str, true);
                    $simpler_string = "{";
                    $len = count($someArray);
                    for($j=0;$j<$len;$j++){
                        if($j == ($len-1)){
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"];    
                        }
                        else{
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"] .",";
                        }
                    }
                    $simpler_string .= "}";
                    $result_to_send[$i]["Payment Mode"] =  $simpler_string;                    
                }             
            }

            $data['result'] = $this->ModelHelper(true,false,'',$result_to_send);
        }



        $package = $this->GetPackageReport($data);
        if($package['success'] == true){
            $arr = [];
            $package = $package['res_arr'];
            $i = 0;
            foreach ($package as $key => $pck) {
                $arr[$i]['Txn Unique Serial Id'] = $pck['Serial Id'];
                $arr[$i]['Billing Date'] = $pck['Purchase Date'];
                $arr[$i]['Mobile No'] = $pck['Customer Mobile'];
                $arr[$i]['Customer Name'] = $pck['Customer Name'];
                $arr[$i]['MRP Amt'] = $pck['Bill Amount'];
                $arr[$i]['Discount'] = $pck['Discount Given'];
                $arr[$i]['Net Amount'] = $pck['Bill Amount'];
                $arr[$i]['Total Tax (Rs.)'] = 0;
                $arr[$i]['Pending Amount'] = 0;
                $arr[$i]['Settlement Way'] = $pck['Settlement Way'];
                $arr[$i]['Payment Mode'] = $pck['Payment Mode'];                
                $arr[$i]["Service Type"] =  "Package";
                $i++;
            }

            if(!empty($arr)){
                $data['result']['res_arr'] = array_merge($data['result']['res_arr'],$arr);
            }            
        }



                return $this->ModelHelper(true,false,'',$data['result']['res_arr']);
    }   

    public function GenerateReports($data){
        if($data['report_name'] == 'OBWSR'){
            return $this->GetOverAllBillWiseSalesReport($data);
        }elseif($data['report_name'] == 'BWSR'){
            return $this->GetBillWiseSalesReport($data);
        }
        elseif ($data['report_name'] == 'DWCSR') {
            return $this->GetDateWiseCategorySalesReport($data);
        } 
       elseif ($data['report_name'] == 'SCWSR') {
            return $this->GetSubCategoryWiseSalesReport($data);
        } 
        elseif ($data['report_name'] == 'PWSR') {
            return $this->GetPackageWiseSalesReport($data);
        } 
        elseif ($data['report_name'] == 'IWSR') {
            return $this->GetItemWiseSalesReport($data);
        } 
        elseif ($data['report_name'] == 'IWCR') {
            return $this->GetItemWiseCustomerReport($data);
        } 
        elseif ($data['report_name'] == 'SROTC') {
            return $this->GetStockReportInventory($data);
        } 
        elseif ($data['report_name'] == 'SRRM') {
            return $this->GetStockReportRawMaterial($data);
        } 
        elseif ($data['report_name'] == 'BWDR') {
            return $this->GetBillWiseDiscountReport($data);
        } 
        elseif ($data['report_name'] == 'EWPR') {
            return $this->GetEmployeeWisePerformanceReport($data);
        } 
        elseif ($data['report_name'] == 'PR') {
            return $this->GetPackageReport($data);
        } 
        elseif($data['report_name'] == 'PWR'){
            return $this->GetPaymentWiseReport($data);
        }
        elseif ($data['report_name'] == 'VWR') {
            return $this->GetVirtualWalletReport($data);
        } 
        elseif($data['report_name'] == 'PAR'){
            return $this->GetPendingAmountReport($data);
        }
         elseif($data['report_name'] == 'PATR'){
            return $this->GetPendingAmountTransactionReport($data);
        }
        elseif($data['report_name']=='AR'){
            return $this->GetAppointmentReport($data);
        }
        elseif($data['report_name']=='TWR'){
            return $this->TransactionInventoryReport($data);
        }
        elseif($data['report_name']=='CT'){
            return $this->CancelledTransaction($data);
		}
		elseif($data['report_name']=='EAR'){
            return $this->EmployeeAttendanceReport($data);
        }
        else{
            $this->ModelHelper(false,true,"No such report exists!");
        }
    }

    private function GetPackageReport($data){
       $sql = "SELECT
                    mss_package_transactions.package_txn_unique_serial_id AS 'Serial Id',
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    date(mss_package_transactions.datetime) AS 'Purchase Date',
                    mss_salon_packages.salon_package_name AS 'Package Name',
                    mss_salon_packages.salon_package_type AS 'Package Type',
                    mss_package_transactions.package_txn_value AS 'Bill Amount',
                    mss_package_transactions.package_txn_discount AS 'Discount Given',
                    mss_package_transactions.package_txn_pending_amount AS 'Pending Amount',
                    mss_package_transaction_settlements.settlement_way AS 'Settlement Way',
                    mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
                    date_add(date(now()),INTERVAL mss_salon_packages.salon_package_validity MONTH) AS 'Expiry Date',
                    mss_employees.employee_first_name AS 'Expert Name'
                FROM
                    mss_package_transactions,
                    mss_customers,
                    mss_salon_packages,
                    mss_transaction_package_details,
                    mss_employees,
                    mss_package_transaction_settlements
                WHERE
                    mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
                    AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_txn_id
                    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
					AND mss_package_transactions.package_txn_expert= mss_employees.employee_id
                    AND mss_salon_packages.business_admin_id =  ".$this->db->escape($data['business_admin_id'])."
                    AND mss_salon_packages.business_outlet_id =  ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_package_transactions.datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                    ORDER BY
                        mss_package_transactions.package_txn_id";


        $query = $this->db->query($sql);
        
        if($query){
            $result = $query->result_array();
            $result_to_send = array();

            for($i=0;$i<count($result);$i++){
                array_push($result_to_send, $result[$i]);
                if($result[$i]["Settlement Way"] == "Split Payment" && $result[$i]["Payment Mode"] != "Split"){
                    
                    $str = $result[$i]["Payment Mode"];
                    
                    $someArray = json_decode($str, true);
                    
                    $simpler_string = "{";
                    
                    $len = count($someArray);
                    
                    for($j=0;$j<$len;$j++){
                        if($j == ($len-1)){
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"];    
                        }
                        else{
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"] .",";
                        }
                    }
                    $simpler_string .= "}";
                    $result_to_send[$i]["Payment Mode"] =  $simpler_string;
                }             
            }

            return $this->ModelHelper(true,false,'',$result_to_send);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetEmployeeWisePerformanceReport($data){
        $sql = "SELECT 
                     mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                     mss_customers.customer_mobile AS 'Customer Mobile',
                     mss_customers.customer_name AS 'Customer Name',
                     date(mss_transactions.txn_datetime) AS 'Billing Date',
                     mss_categories.category_name AS 'Category',
                     mss_sub_categories.sub_category_name AS 'Sub-Category',
                     mss_services.service_name AS 'Service',
                     IFNULL(mss_services.inventory_type,'Service') AS 'Type' ,
                     ROUND(mss_services.service_price_inr+(mss_services.service_price_inr*mss_services.service_gst_percentage/100)) AS 'MRP',
                     mss_transaction_services.txn_service_quantity AS 'Quantity',
                     (((mss_services.service_price_inr+(mss_services.service_price_inr*mss_services.service_gst_percentage/100))*mss_transaction_services.txn_service_discount_percentage/100)*mss_transaction_services.txn_service_quantity+mss_transaction_services.txn_service_discount_absolute) AS 'Discount',
                     mss_employees.employee_first_name As 'Expert Name',
                     mss_transaction_services.txn_service_discounted_price AS 'Billing Amount'
                     -- mss_transactions.txn_value AS 'Net Bill Amt'
                FROM
                     mss_transactions,
                     mss_transaction_services,
                     mss_employees,
                     mss_customers,
                     mss_categories,
                     mss_sub_categories,
                     mss_services
                WHERE
                     mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
					 AND mss_transactions.txn_status=1
                     AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                     AND mss_transaction_services.txn_service_service_id = mss_services.service_id
					 AND mss_transaction_services.txn_service_status = 1
                     AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                     AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                     AND mss_transactions.txn_customer_id = mss_customers.customer_id
                     AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
                     AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
                     AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                     ORDER BY
                        mss_transactions.txn_id";
                        #echo $sql;die;
        $query = $this->db->query($sql); 
        
        if($query){
            $result1 = $query->result_array();

            $sql = "SELECT
                    mss_package_transactions.package_txn_unique_serial_id AS 'Txn Unique Serial Id',                    
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_customers.customer_name AS 'Customer Name',
                    date(mss_package_transactions.datetime) AS 'Billing Date',
                    mss_salon_packages.salon_package_name AS 'Service',
                    mss_salon_packages.salon_package_type AS 'Package Type',
                    mss_salon_packages.salon_package_name AS 'Sub-Category',
                    IF(mss_package_transactions.package_txn_unique_serial_id,'Package','Package') AS 'Type' ,
                    mss_package_transactions.package_txn_value AS 'Bill Amount',    
                    IF(mss_salon_packages.salon_package_type,'','') AS 'Quantity',
                    mss_package_transactions.package_txn_discount AS 'Discount Given',
                    mss_employees.employee_first_name AS 'Expert Name',
                    -- mss_package_transactions.package_txn_pending_amount AS 'Pending Amount',
                    mss_package_transactions.package_txn_value AS 'Billing Amount'
                    -- mss_package_transactions.package_txn_value  AS 'Net Bill Amt'
                    -- mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
                    -- date_add(date(now()),INTERVAL mss_salon_packages.salon_package_validity MONTH) AS 'Expiry Date'                    
                FROM
                    mss_package_transactions,
                    mss_customers,
                    mss_salon_packages,
                    mss_transaction_package_details,
                    mss_employees,
                    mss_package_transaction_settlements
                WHERE
                    mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
                    AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_txn_id
                    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                    AND mss_package_transactions.package_txn_expert= mss_employees.employee_id
                    AND mss_salon_packages.business_admin_id =  ".$this->db->escape($data['business_admin_id'])."
                    AND mss_salon_packages.business_outlet_id =  ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_package_transactions.datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                    ORDER BY
                        mss_package_transactions.package_txn_id";
                        $query = $this->db->query($sql); 
                        $result2 = $query->result_array();
                        $result = array_merge($result1,$result2);
                        // print_r($result1);
                        // print_r($result2);
                        // die;
            return $this->ModelHelper(true,false,'',$result);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }    

    private function GetBillWiseDiscountReport($data){
        $sql = "SELECT 
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
                    mss_employees.employee_last_name AS 'Expert Last Name'
                FROM 
                    mss_transactions,
                    mss_transaction_services,
                    mss_customers,
                    mss_employees,
                    mss_services
                WHERE
                    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                ORDER BY
                    mss_transactions.txn_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }    

    private function GetStockReportRawMaterial($data){
        $sql = "SELECT 
                    mss_raw_material_categories.raw_material_category_id AS 'Raw Material Category Code',
                    mss_raw_material_categories.raw_material_name AS 'Raw Material Name',
                    mss_raw_material_categories.raw_material_brand AS 'Brand',
                    mss_raw_material_categories.raw_material_type AS 'Material Type',
                    mss_raw_material_stock.rm_entry_date AS 'Entry Date',
                    mss_raw_material_stock.rm_expiry_date AS 'Expiry Date',
                    mss_raw_material_stock.rm_stock AS 'Stock As On date',
                    mss_raw_material_categories.raw_material_unit AS 'Unit'
                FROM 
                    mss_raw_material_categories,
                    mss_raw_material_stock 
                WHERE 
                    mss_raw_material_categories.raw_material_category_id = mss_raw_material_stock.rmc_id 
                    AND mss_raw_material_categories.raw_material_business_outlet_id = ".$this->db->escape($data['business_admin_id'])." 
                    AND mss_raw_material_categories.raw_material_business_admin_id = ".$this->db->escape($data['business_outlet_id'])." 
                    AND mss_raw_material_categories.raw_material_is_active = 1";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetStockReportOTC($data){
        $sql = "SELECT 
                    mss_services.service_name AS 'Service',
                    mss_services.service_id AS 'Item Code',
                    mss_services.service_brand AS 'Brand',
                    mss_services.service_type AS 'Type',
                    mss_otc_stock.otc_sku AS 'Current Stock',
                    mss_otc_stock.otc_expiry_date AS 'Expiry Date'

                FROM 
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_otc_stock 
                WHERE 
                    mss_categories.category_id = mss_sub_categories.sub_category_category_id 
                    AND mss_sub_categories.sub_category_id = mss_services.service_sub_category_id 
                    AND mss_services.service_id = mss_otc_stock.otc_service_id 
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_services.service_is_active = 1 
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
                    AND mss_services.service_type = 'otc'
                ORDER BY
                    mss_otc_stock.otc_expiry_date DESC";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetItemWiseCustomerReport($data){
        $sql = "SELECT 
                    mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_categories.category_name AS 'Category',
                    mss_sub_categories.sub_category_name 'Sub-Category',
                    mss_services.service_name AS 'Service',
                    mss_transaction_services.txn_service_discounted_price AS 'Discounted Service Price',
                    TRUNCATE(mss_services.service_price_inr + ((mss_services.service_gst_percentage/100) * mss_services.service_price_inr),2)  AS 'Service MRP'
                FROM 
                    mss_transactions,
                    mss_customers,
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_transaction_services
                WHERE
                    mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                ORDER BY mss_transactions.txn_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetItemWiseSalesReport($data){
        $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_categories.category_name AS 'Category',
                    mss_sub_categories.sub_category_name AS 'Sub-Category',
                    mss_services.service_name AS 'Service',
                    mss_employees.employee_first_name AS 'expert',
                    COUNT(mss_services.service_id) AS 'Bill Count',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Amt'
                FROM
                    mss_transactions,
                    mss_transaction_services,
                    mss_categories,
                    mss_sub_categories,
                    mss_services,
                    mss_employees
                WHERE
                    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transaction_services.txn_service_expert_id=mss_employees.employee_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                GROUP BY
                    date(mss_transactions.txn_datetime),
                    mss_categories.category_id,
                    mss_sub_categories.sub_category_id,
                    mss_services.service_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetPackageWiseSalesReport($data){
        $sql = "SELECT 
                    date(mss_package_transactions.datetime) AS 'Billing Date',
                    mss_salon_packages.salon_package_name AS 'Package Name',
                    mss_salon_packages.salon_package_type AS 'Type',
                    COUNT(mss_salon_packages.salon_package_id) AS 'Package Sold Count',
                    SUM(mss_package_transactions.package_txn_value) AS 'Bill Amt'
                FROM
                    mss_package_transactions,
                    mss_salon_packages,
                    mss_transaction_package_details
                WHERE
                    mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
                    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_salon_packages.business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_salon_packages.business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_package_transactions.datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                GROUP BY
                    date(mss_package_transactions.datetime),
                    mss_salon_packages.salon_package_name,
                    mss_salon_packages.salon_package_type";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetSubCategoryWiseSalesReport($data){
        $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Billing Date', 
                    mss_categories.category_name AS 'Category', 
                    mss_sub_categories.sub_category_name AS 'Sub-Category',
                    COUNT(mss_sub_categories.sub_category_id) AS 'Total Sub Category',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Amount' 
                FROM 
                    mss_transactions, 
                    mss_transaction_services, 
                    mss_categories, 
                    mss_sub_categories,
                    mss_services
                WHERE 
                    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])." 
                GROUP BY 
                    date(mss_transactions.txn_datetime), 
                    mss_categories.category_id,
                    mss_sub_categories.sub_category_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetBillWiseSalesReport($data){
        $sql = "SELECT 
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
        mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode'
        
    FROM 
        mss_customers,
        mss_transactions,
        mss_transaction_settlements,
        mss_employees
    WHERE 
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
        AND mss_transactions.txn_cashier= mss_employees.employee_id
        AND mss_transactions.txn_status=1
        AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
        AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])." 
        AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])." GROUP BY mss_transactions.txn_id ";
        

        $query = $this->db->query($sql);
        
        if($query){
            $result = $query->result_array();
            $result_to_send = array();

            for($i=0;$i<count($result);$i++){
                array_push($result_to_send, $result[$i]);
                if($result[$i]["Settlement Way"] == "Split Payment" && $result[$i]["Payment Mode"] != "Split"){
                    $str = $result[$i]["Payment Mode"];
                    $someArray = json_decode($str, true);
                    $simpler_string = "{";
                    $len = count($someArray);
                    for($j=0;$j<$len;$j++){
                        if($j == ($len-1)){
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"];    
                        }
                        else{
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"] .",";
                        }
                    }
                    $simpler_string .= "}";
                    $result_to_send[$i]["Payment Mode"] =  $simpler_string;
                }             
            }

            return $this->ModelHelper(true,false,'',$result_to_send);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetDateWiseCategorySalesReport($data){
        $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_categories.category_name AS 'Category Name',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Sales(Rs.)'
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
                  AND mss_business_outlets.business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                  AND mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                  AND mss_business_admin.business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                  AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                GROUP BY 
                    date(mss_transactions.txn_datetime), 
                    mss_categories.category_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetPaymentWiseReport($data){
        $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Payment Date',          
                    mss_transaction_settlements.txn_settlement_way AS 'Settlement Way',
                    (mss_transactions.txn_value) AS 'Total Amount'
                FROM 
                   mss_transactions, 
                   mss_transaction_settlements, 
                   mss_customers,
                   mss_employees
                WHERE 
                    mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id 
                    AND mss_transactions.txn_customer_id = mss_customers.customer_id 
                    AND mss_transactions.txn_cashier= mss_employees.employee_id
                    AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetVirtualWalletReport($data){
        $sql = "SELECT
                    mss_customers.customer_name As 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_customers.customer_virtual_wallet AS 'Virtual Wallet',
                    IFNULL(mss_customers.customer_wallet_expiry_date,'') AS 'Expiry Date'
                FROM
                    mss_customers,
                    mss_business_outlets,
                   mss_business_admin
                WHERE
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                    AND mss_business_admin.business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_customers.customer_virtual_wallet > 0";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetPendingAmountReport($data){
        $sql = "SELECT 
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_customers.customer_pending_amount AS 'Pending Amount'
                FROM
                    mss_customers,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_customers.customer_pending_amount > 0 
                     AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                    AND mss_business_admin.business_admin_id = ".$this->db->escape($data['business_admin_id'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    
    
    private function GetPendingAmountTransactionReport($data){
         $sql = "SELECT 
                    mss_customers.customer_name AS 'Customer Name',
                    mss_customers.customer_mobile AS 'Customer Mobile',
                    mss_pending_amount_tracker.pending_amount_submitted AS 'Cleared Pending Amount',
                    mss_pending_amount_tracker.pending_amount_outstanding AS 'Pending Amount Outstanding',
                    mss_pending_amount_tracker.date_time AS 'Date & Time',
					mss_pending_amount_tracker.payment_type
                FROM
                    mss_customers,
                    mss_pending_amount_tracker,
                    mss_business_outlets,
                    mss_business_admin
                WHERE
                    mss_pending_amount_tracker.customer_id = mss_customers.customer_id
                    AND mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
                    AND mss_business_outlets.business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
                    AND mss_business_admin.business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND date(mss_pending_amount_tracker.date_time) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }      
    }
    //Appointment Report
     private function GetAppointmentReport($data){
         $sql = "SELECT
			mss_appointments.appointment_date AS 'Appointment Date',
			mss_appointments.appointment_start_time AS 'Appointment Time',
			mss_appointments.appointment_status AS 'Billed=1/Canceled=0',
			mss_customers.customer_name AS 'Customer Name',
			mss_customers.customer_mobile AS 'Mobile',
			mss_employees.employee_first_name AS 'Expert',
			mss_services.service_name AS 'Service',
			mss_services.service_price_inr AS 'MRP'
			FROM
				mss_appointments,
				mss_appointment_services,
				mss_customers,
				mss_employees,
				mss_services
			WHERE
				mss_appointments.customer_id=mss_customers.customer_id
			AND	
				mss_appointment_services.appointment_id=mss_appointments.appointment_id
			AND
				mss_appointment_services.service_id=mss_services.service_id
			AND	
				mss_appointment_services.expert_id=mss_employees.employee_id
			AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
			AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
			AND date(mss_appointments.appointment_date) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."";


        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }      
    }
    
    public function GetTopCardsData($data){
        switch ($data['type']) {
            case 'sales':
                $sql = "SELECT 
						SUM(mss_transaction_services.txn_service_discounted_price) AS sales
						FROM
						mss_transactions,
						mss_transaction_settlements,
						mss_customers,
						mss_transaction_services,
						mss_services,
						mss_employees
						WHERE
						mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
						AND mss_transactions.txn_customer_id = mss_customers.customer_id
						AND mss_transactions.txn_cashier= mss_employees.employee_id
						AND mss_transaction_services.txn_service_txn_id= mss_transaction_settlements.txn_settlement_txn_id
						AND mss_transaction_services.txn_service_service_id=mss_services.service_id
						AND mss_transaction_services.txn_service_status= 1
						AND mss_services.service_type= 'service'
						AND date(mss_transactions.txn_datetime) = date(now())
						AND mss_transactions.txn_status=1				
						AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])." 
						AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";
						//
						
						//
				break;
				case 'productsales':
					$sql = "SELECT 
							SUM(mss_transaction_services.txn_service_discounted_price) AS productsales
							FROM
							mss_transactions,
							mss_transaction_settlements,
							mss_customers,
							mss_transaction_services,
							mss_services,
							mss_employees
							WHERE
							mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
							AND mss_transactions.txn_customer_id = mss_customers.customer_id
							AND mss_transactions.txn_cashier= mss_employees.employee_id
							AND mss_transaction_services.txn_service_txn_id= mss_transaction_settlements.txn_settlement_txn_id
							AND mss_transaction_services.txn_service_service_id=mss_services.service_id
							AND mss_services.service_type= 'otc'
							AND date(mss_transactions.txn_datetime) = date(now())
							AND mss_transactions.txn_status=1				
							AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])." 
							AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";
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
							AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
							AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."";
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
				mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
				AND mss_customers.customer_business_outlet_id =".$this->db->escape($data['business_outlet_id'])." 
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
                                AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
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
                            AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
                            AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
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
                                AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
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
                            AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                            AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
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
							AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
							AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
							AND mss_expenses.expense_date = SUBDATE(CURDATE(),1)";
				break;
            default:
                return $this->ModelHelper(false,true,"Wrong choice!");
                break;
        }

        
			$query = $this->db->query($sql);
					
					if($query){
							return $this->ModelHelper(true,false,'',$query->row_array());
					}
					else{
							return $this->ModelHelper(false,true,"DB error!");   
					}
			}


    public function GetSalesPaymentWiseData($where){
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
						SUM(mss_transaction_settlements.txn_settlement_amount_received-mss_transaction_settlements.txn_settlement_balance_paid) AS 'Sum Amount'
					FROM 
						mss_transactions,
						mss_transaction_settlements,
						mss_customers
					WHERE
		 			mss_transactions.txn_status=1
					AND mss_transactions.txn_customer_id = mss_customers.customer_id
					AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
					AND mss_transaction_settlements.txn_settlement_way = 'Full Payment'
					AND mss_customers.customer_business_admin_id = ".$this->db->escape($where['business_admin_id'])."
					AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."
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
                    mss_customers,
					mss_employees 
                WHERE 
                    mss_transactions.txn_customer_id = mss_customers.customer_id 
                    AND mss_transactions.txn_status=1
					AND mss_transactions.txn_cashier =mss_employees.employee_id 
                    AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                    AND mss_transaction_settlements.txn_settlement_way = 'Split Payment'
                    AND mss_employees.employee_business_admin = ".$this->db->escape($where['business_admin_id'])."
                    AND mss_employees.employee_business_outlet = ".$this->db->escape($where['business_outlet_id'])."
                    AND date(mss_transactions.txn_datetime) = date(now())";

        $query2 = $this->db->query($sql2);

        $data_split_payment = $query2->result_array();
        foreach ($data_split_payment as $k) {
            $str = $k["Payment Mode"];
                    
            $someArray = json_decode($str, true);
                                        
            $len = count($someArray);
                    
            for($j=0;$j<$len;$j++){
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
        $data['paid_back']=$data_split_payment[0]['paid_back'];
        return $this->ModelHelper(true,false,'',$data);
    }

    public function GetLowStockItems($data){
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
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
                    AND mss_services.service_type = 'otc'
                    AND mss_otc_stock.otc_sku < 15";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }

    public function GetGenderDistribution($data){
         $sql = "SELECT 
                    mss_customers.customer_title, 
                    COUNT(mss_customers.customer_id) AS count_gender
                FROM 
                    mss_customers
                WHERE
                    mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                GROUP BY 
                    mss_customers.customer_title";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }     
    }

    public function GetAgeDistribution($data){
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
                        mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                        AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
                ) t
                GROUP BY t.age_group";
                    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }       
    }


    public function GetRevenueBarChart($data){
        $sql = "SELECT 
                    date(mss_transactions.txn_datetime) as bill_date, 
                    SUM(mss_transactions.txn_value) AS sales 
                FROM 
                    mss_transactions,
                    mss_customers 
                WHERE 
                    mss_transactions.txn_customer_id = mss_customers.customer_id 
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                    AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                GROUP BY 
                    date(mss_transactions.txn_datetime)
                ORDER BY
                  date(mss_transactions.txn_datetime)
                LIMIT 15";
                    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }  
    }
		//package revenue
		public function GetPackageRevenueBarChart($data){
			$sql = "SELECT 
					date(mss_package_transactions.datetime) as bill_date, 
					SUM(mss_package_transactions.package_txn_value) AS sales 
			FROM 
					mss_package_transactions,
					mss_customers 
			WHERE 
				mss_package_transactions.package_txn_customer_id= mss_customers.customer_id 
					AND date(mss_package_transactions.datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
					AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
					AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
			GROUP BY 
					date(mss_package_transactions.datetime)
			ORDER BY
				date(mss_package_transactions.datetime)
			LIMIT 15";
									
			$query = $this->db->query($sql);
			
			if($query){
					return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
					return $this->ModelHelper(false,true,"DB error!");   
			}  
	}

    public function GetCustomerBarChart($data){
        $sql = "SELECT 
                   date(mss_customers.customer_added_on) as add_date,
                   COUNT(mss_customers.customer_id) as total
                FROM 
                    mss_customers
                WHERE
                    mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_customers.customer_added_on BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                GROUP BY
                    date(mss_customers.customer_added_on)
                ORDER BY
                    date(mss_customers.customer_added_on)
                LIMIT 7";
                
                    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        } 
    }  

    public function GetVisitsBarChart($data){
        $sql = "SELECT
                    date(mss_transactions.txn_datetime) as visit_date,
                    COUNT(mss_transactions.txn_id) AS 'total_visits'
                FROM 
                    mss_transactions,
                    mss_customers
                WHERE
                    mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                GROUP BY
                    date(mss_transactions.txn_datetime)
                ORDER BY
                    date(mss_transactions.txn_datetime)
                LIMIT 7";
                
                    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        } 
    }

    public function BarChartYearly($data){
        $sql = "";
        switch ($data['yearly_kpi']) {
            case 'revenue':
                if(!empty($data['service_id']) && isset($data['service_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_services.service_id = ".$this->db->escape($data['service_id'])."
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                elseif(!empty($data['sub_category_id']) && isset($data['sub_category_id'])){
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
                              AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                              AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                              AND mss_sub_categories.sub_category_id = ".$this->db->escape($data['sub_category_id'])."
                              AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                          GROUP BY
                              YEAR(date(mss_transactions.txn_datetime)),
                              MONTH(date(mss_transactions.txn_datetime))";
                }
                elseif(!empty($data['category_id']) && isset($data['category_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_categories.category_id = ".$this->db->escape($data['category_id'])." 
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                break;
            case 'customers':
                if(!empty($data['service_id']) && isset($data['service_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_services.service_id = ".$this->db->escape($data['service_id'])."
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                elseif(!empty($data['sub_category_id']) && isset($data['sub_category_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_sub_categories.sub_category_id = ".$this->db->escape($data['sub_category_id'])."
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                elseif(!empty($data['category_id']) && isset($data['category_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_categories.category_id = ".$this->db->escape($data['category_id'])."
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                break;
            case 'visits':
                if(!empty($data['service_id']) && isset($data['service_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_services.service_id = ".$this->db->escape($data['service_id'])."
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                elseif(!empty($data['sub_category_id']) && isset($data['sub_category_id'])){
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
                                AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                                AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                                AND mss_sub_categories.sub_category_id = ".$this->db->escape($data['sub_category_id'])."
                                AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                elseif(!empty($data['category_id']) && isset($data['category_id'])){
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
                            AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                            AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                            AND mss_categories.category_id = ".$this->db->escape($data['category_id'])."
                            AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
                            GROUP BY
                                YEAR(date(mss_transactions.txn_datetime)),
                                MONTH(date(mss_transactions.txn_datetime))";
                }
                break;
            default:
                return $this->ModelHelper(false,true,"Wrong choice!");
        }
       
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        } 
	}  
	//Business Admin Existing Loyalty
	public function ExistingLoyalty($data){
		$sql="SELECT mss_loyalty.* FROM `mss_loyalty` WHERE mss_loyalty.loyalty_business_admin_id=".$this->db->escape($data['business_admin_id'])."
		";

		$query = $this->db->query($sql);
		
		if($query->num_rows()>0){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			$data=array(
				'loyalty_business_admin_id'=>$data['business_admin_id'],
				'loyalty_conversion'=>100,
				'loyalty_rate'=>0.00,
				'validity'=>999
			);
			$res=$this->Insert($data,'mss_loyalty');
			$sql="SELECT mss_loyalty.* FROM `mss_loyalty` WHERE mss_loyalty.loyalty_business_admin_id=".$this->db->escape($data['business_admin_id'])."
			";

			$query = $this->db->query($sql);
			return $this->ModelHelper(true,false,'',$query->result_array());   
		} 
	}
	//Business Admin Existing Loyalty
	public function Cashback_Customer_Data($data){
		$sql="SELECT		
		mss_customers.customer_name,	
		mss_customers.customer_mobile,
		mss_customers.customer_rewards,
		mss_customers.customer_cashback,
    	SUM(mss_transactions.txn_value)'total_spent',
		MAX(date(mss_transactions.txn_datetime))'last_txn_date'
		FROM
			mss_customers,
			mss_transactions
		WHERE
			mss_customers.customer_id=mss_transactions.txn_customer_id
		AND
		mss_transactions.txn_status=1 AND
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])."
		GROUP BY mss_customers.customer_id
		ORDER BY date(mss_customers.customer_last_updated) DESC
			LIMIT 10		
			";

			$query = $this->db->query($sql);
			
			if($query->num_rows()){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(true,false,'Database Error');   
			} 
    }
    //
    public function SearchCustomers($search_term,$business_admin_id){

      $sql = "SELECT 
        customer_id,customer_name,customer_mobile 
      FROM 
      `mss_customers` 
      WHERE 
      customer_business_admin_id = ".$this->db->escape($business_admin_id)." AND (customer_name LIKE '%$search_term%' OR customer_mobile  LIKE '%$search_term%') ORDER BY customer_name LIMIT 10";
      
      $query = $this->db->query($sql);
      
      if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
          return $this->ModelHelper(false,true,"DB error!");   
      }
	}
	//CAshback Customerdata by ID
	public function Cashback_Customer_Data_By_Id($data){
		$sql="SELECT		
		mss_customers.customer_name,	
		mss_customers.customer_mobile,
		mss_customers.customer_rewards,
		mss_customers.customer_cashback,
		SUM(mss_transactions.txn_value)'total_spent',
		MAX(date(mss_transactions.txn_datetime))'last_txn_date'
		FROM
			mss_customers,
			mss_transactions
		WHERE
			mss_customers.customer_id=".$this->db->escape($data['customer_id'])."
			AND
			mss_customers.customer_id=mss_transactions.txn_customer_id
		AND
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])."
			";

			$query = $this->db->query($sql);
			
			if($query->num_rows()){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(true,false,'Database Error');   
			} 
    }
    
    //Business Admin Today's bill
	public function GetTodaysBill($data){
		$sql="SELECT 
		mss_transactions.txn_id AS 'bill_no',
		mss_transactions.txn_unique_serial_id AS 'Txn_id',
		date(mss_transactions.txn_datetime) AS 'billing_date',
		mss_customers.customer_mobile AS 'mobile',
		mss_customers.customer_name AS 'name',
		(mss_transactions.txn_discount+mss_transactions.txn_value) AS 'mrp_amt',
		mss_transactions.txn_discount AS 'discount',
		mss_transactions.txn_value AS 'net_amt',
		mss_transactions.txn_total_tax AS 'total_tax',
		mss_transactions.txn_pending_amount AS 'pending_amt',
		mss_transaction_settlements.txn_settlement_way AS 'settlement_way',
		mss_transaction_settlements.txn_settlement_payment_mode AS 'payment_way'
		
		FROM 
		mss_customers,
		mss_transactions,
		mss_transaction_settlements,
		mss_employees
		WHERE 
		mss_transactions.txn_customer_id = mss_customers.customer_id
		AND mss_transactions.txn_status=1
		AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
		AND mss_transactions.txn_cashier= mss_employees.employee_id
		AND date(mss_transactions.txn_datetime) = date(now())
		AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
		AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
			";

        $query = $this->db->query($sql);
        
        if($query->num_rows()){
          return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
          return $this->ModelHelper(true,false,'Database Error');   
        } 
      }
    
    //Generate Bills
    public function GenerateBills($data){
        $sql="SELECT 
          mss_transactions.txn_id AS 'bill_no',
          mss_transactions.txn_status,
          mss_transactions.txn_unique_serial_id AS 'txn_id',
          date(mss_transactions.txn_datetime) AS 'billing_date',
          mss_customers.customer_mobile AS 'mobile',
          mss_customers.customer_name AS 'name',
          IF(mss_transactions.txn_id,'Service','Service') AS 'Type' ,
          (mss_transactions.txn_discount+mss_transactions.txn_value) AS 'mrp_amt',
          mss_transactions.txn_discount AS 'discount',
          mss_transactions.txn_value AS 'net_amt',
          mss_transactions.txn_total_tax AS 'total_tax',
          mss_transactions.txn_pending_amount AS 'pending_amt',
          mss_transaction_settlements.txn_settlement_way AS 'settlement_way',
          mss_transaction_settlements.txn_settlement_payment_mode AS 'payment_way'
      
        FROM 
        mss_customers,
        mss_transactions,
        mss_transaction_settlements,
        mss_employees
        WHERE 
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND mss_transactions.txn_cashier = mss_employees.employee_id
        AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
        AND mss_employees.employee_business_admin= ".$this->db->escape($data['business_admin_id'])."
        AND mss_employees.employee_business_outlet= ".$this->db->escape($data['business_outlet_id'])."
        AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."";

        $query = $this->db->query($sql);
        
        if($query->num_rows()){

            $result1 = $query->result_array();            
            $sql = "SELECT
                    mss_package_transactions.package_txn_id AS 'bill_no',
                    mss_package_transactions.package_txn_unique_serial_id AS 'txn_id',
                    date(mss_package_transactions.datetime) AS 'billing_date',
                    mss_customers.customer_mobile AS 'mobile',
                    mss_customers.customer_name AS 'name',            
                    -- IF(mss_package_transactions.package_txn_id,'1','1') AS 'txn_status' ,
                    IF(mss_package_transactions.package_txn_id,'Package','Package') AS 'Type' ,
                    mss_package_transactions.package_txn_value AS 'mrp_amt',  
                    mss_package_transactions.package_txn_discount AS 'discount',
                    mss_package_transactions.package_txn_value AS 'net_amt',
                    IF(mss_salon_packages.service_gst_percentage,'0','0') AS 'total_tax',
                    mss_package_transactions.package_txn_pending_amount AS 'pending_amt',
                     mss_package_transaction_settlements.settlement_way AS 'settlement_way',
                    mss_package_transaction_settlements.payment_mode AS 'payment_way'
                    -- mss_salon_packages.salon_package_name AS 'Service',
                    -- mss_salon_packages.salon_package_type AS 'Package Type',
                    -- mss_salon_packages.salon_package_name AS 'Sub-Category',
                    -- IF(mss_package_transactions.package_txn_unique_serial_id,'Package','Package') AS 'Type' ,
                      
                    -- IF(mss_salon_packages.salon_package_type,'','') AS 'Quantity',
                    
                    -- mss_employees.employee_first_name AS 'Expert Name',
                    
                    
                    
                FROM
                    mss_package_transactions,
                    mss_customers,
                    mss_salon_packages,
                    mss_transaction_package_details,
                    mss_employees,
                    mss_package_transaction_settlements
                WHERE
                    mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
                    AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_txn_id
                    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
                    AND mss_package_transactions.package_txn_expert= mss_employees.employee_id
                    AND mss_salon_packages.business_admin_id =  ".$this->db->escape($data['business_admin_id'])."
                    AND mss_salon_packages.business_outlet_id =  ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_package_transactions.datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                    ORDER BY
                        mss_package_transactions.package_txn_id desc";  
                        
                        $query = $this->db->query($sql); 
                    $result2 = $query->result_array();
                    $result = array_merge($result1,$result2);


          return $this->ModelHelper(true,false,'',$result);
        }
        else{
          return $this->ModelHelper(true,false,'Database Error');   
        } 
      }
	  

	  //Cancel Bills
	  public function CancelBills($data){
		$sql="UPDATE mss_transactions SET txn_status='0' txn_remarks=".$this->db->escape($data['txn_remarks'])." WHERE mss_transactions.txn_unique_serial_id=".$this->db->escape($data['txn_id'])." ";
	
			$query = $this->db->query($sql);
			
			if($query){
			  return $this->ModelHelper(true,false,'');
			}
			else{
			  return $this->ModelHelper(true,false,'Database Error');   
			} 

		}

		public function Holidays($data){
			$sql="SELECT *				
			FROM 
				mss_emss_holidays
			WHERE
			mss_emss_holidays.holiday_status=1 AND
			mss_emss_holidays.holiday_business_admin_id = ".$this->db->escape($data['business_admin_id'])." ";
	
					$query = $this->db->query($sql);
					
					if($query->num_rows()){
						return $this->ModelHelper(true,false,'',$query->result_array());
					}
					else{
						return $this->ModelHelper(true,false,'Database Error');   
					} 
    }
      
    public function GetAttendance($data){
        $sql="SELECT *				
        FROM 
          mss_emss_attendance
        WHERE
        mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])." AND
        mss_emss_attendance.employee_business_admin_id = ".$this->db->escape($data['business_admin_id'])." ";
    
            $query = $this->db->query($sql);
            
            if($query->num_rows()){
              return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
              return $this->ModelHelper(true,false,'Database Error');   
            } 
		}
		
	// autoengage
	public function ActiveTriggers($data){
		$sql="SELECT 
		mss_auto_engage.auto_engage_id,
		mss_auto_engage.business_admin_id,
		mss_auto_engage.business_outlet_id,
		mss_auto_engage.trigger_type,
		mss_auto_engage.trigger_days,
		mss_auto_engage.offer_type,
		mss_auto_engage.is_active,
		mss_business_outlets.business_outlet_name 
		FROM 
		mss_auto_engage,
		mss_business_outlets 
		WHERE 
		mss_business_outlets.business_outlet_id=mss_auto_engage.business_outlet_id
		AND
		mss_auto_engage.business_admin_id=".$this->db->escape($data['business_admin_id'])."
		";

		$query = $this->db->query($sql);
	
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		} 
	}
	//
	public function UpcomingDate($data){
		$sql="SELECT 
		mss_customers.customer_name,
		mss_customers.customer_mobile,
		mss_customers.customer_dob
		FROM 
		mss_customers 
		WHERE 
		Extract(MONTH FROM mss_customers.customer_dob)= Extract(MONTH FROM date(now()))
		AND
		mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])."
		";

		$query = $this->db->query($sql);
	
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		} 
	}
	//Package Sales
	public function TodayPackageSales($data){
		$sql = "SELECT 
					SUM(mss_package_transaction_settlements.amount_received) AS package_sales
					FROM
					mss_package_transactions,
					mss_package_transaction_settlements,
					mss_customers,
					mss_employees
					WHERE
					mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
					AND mss_package_transactions.package_txn_cashier = mss_employees.employee_id
					AND date(mss_package_transactions.datetime) = date(now())
					AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])." 
					AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";

		$query = $this->db->query($sql);
	
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		} 
	}
	public function GetPackageSalesPaymentWiseData($where){
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
		mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
		SUM(mss_package_transaction_settlements.amount_received) AS 'Sum Amount'
		FROM 
			mss_package_transactions,
			mss_package_transaction_settlements,
			mss_customers
		WHERE
		mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
		AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_transaction_settlement_id
		AND mss_customers.customer_business_admin_id =  ".$this->db->escape($where['business_admin_id'])."
		AND mss_customers.customer_business_outlet_id =  ".$this->db->escape($where['business_outlet_id'])."
		AND date(mss_package_transactions.datetime) = date(now())
		GROUP BY mss_package_transaction_settlements.payment_mode";

		$query1 = $this->db->query($sql1);

		$package_full_payment = $query1->result_array();
			foreach ($package_full_payment as $k) {
					switch ($k['Payment Mode']) {
							case 'Cash':
									$data['cash'] += (int) $k['Sum Amount'];
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
			mss_package_transaction_settlements.payment_mode AS 'Payment Mode'
		FROM
			mss_package_transactions,
			mss_package_transaction_settlements,
			mss_employees
		WHERE 
			mss_package_transactions.package_txn_id= mss_package_transaction_settlements.package_txn_id AND
			mss_package_transaction_settlements.settlement_way='Split Payment' AND 
			mss_package_transactions.package_txn_cashier=mss_employees.employee_id AND
			mss_employees.employee_business_outlet=".$this->db->escape($where['business_outlet_id'])." AND
			mss_employees.employee_business_admin=".$this->db->escape($where['business_admin_id'])." AND 
			date(mss_package_transactions.datetime)= CURRENT_DATE ";

		$query2 = $this->db->query($sql2);
		$package_split_payment = $query2->result_array();
		
		foreach ($package_split_payment as $k) {
				$str = $k["Payment Mode"];
				$someArray=array();
				$someArray = json_decode($str, true);										
				$len = count($someArray);
				for($i=0;$i<$len;$i++){
						switch ($someArray[$i]["payment_type"]) {
								case 'Cash':
										$data['cash']           += (int) $someArray[$i]["amount_received"];
										break;
								case 'Credit_Card':
										$data['credit_card']    += (int) $someArray[$i]["amount_received"];
										break;
								case 'Debit_Card':
										$data['debit_card']     += (int) $someArray[$i]["amount_received"];
										break;
								case 'Paytm':
										$data['paytm']          += (int) $someArray[$i]["amount_received"];
										break;
								case 'Phonepe':
										$data['phone_pe']       += (int) $someArray[$i]["amount_received"];
										break;
								case 'Google_Pay':
										$data['google_pay']     += (int) $someArray[$i]["amount_received"];
										break;
								case 'Virtual_Wallet':
										$data['virtual_wallet'] += (int) $someArray[$i]["amount_received"];
										break;
								default:
										$data['others']         += (int) $someArray[$i]["amount_received"];
										break;
						}
				}
		}

		return $this->ModelHelper(true,false,'',$data);
	}
	public function GetMonthlyPackageSalesPaymentWiseData($where){
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
		mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
		SUM(mss_package_transaction_settlements.amount_received) AS 'Sum Amount'
		FROM 
			mss_package_transactions,
			mss_package_transaction_settlements,
			mss_customers
		WHERE
		date(mss_package_transactions.datetime) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND
		mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
		AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_transaction_settlement_id
		AND mss_package_transaction_settlements.settlement_way = 'Full Payment'
		AND mss_customers.customer_business_admin_id =  ".$this->db->escape($where['business_admin_id'])."
		AND mss_customers.customer_business_outlet_id =  ".$this->db->escape($where['business_outlet_id'])."
		GROUP BY mss_package_transaction_settlements.payment_mode";

		$query1 = $this->db->query($sql1);

		$package_full_payment = $query1->result_array();
			foreach ($package_full_payment as $k) {
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
					mss_package_transaction_settlements.payment_mode AS 'Payment Mode'
				FROM 
					mss_package_transactions, 
					mss_package_transaction_settlements, 
					mss_customers 
				WHERE 
					date(mss_package_transactions.datetime) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND
					mss_package_transactions.package_txn_customer_id = mss_customers.customer_id 
					AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_transaction_settlement_id
					AND mss_package_transaction_settlements.settlement_way = 'Split Payment'
					AND mss_customers.customer_business_admin_id =".$this->db->escape($where['business_admin_id'])."
					AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."
					";

		$query2 = $this->db->query($sql2);
		$package_split_payment = $query2->result_array();
		foreach ($package_split_payment as $k) {
				$str = $k["Payment Mode"];
				$someArray=array();
				$someArray = json_decode($str, true);										
				$len = count($someArray);
				for($i=0;$i<$len;$i++){
						switch ($someArray[$i]["payment_type"]) {
								case 'Cash':
										$data['cash']           += (int) $someArray[$i]["amount_received"];
										break;
								case 'Credit_Card':
										$data['credit_card']    += (int) $someArray[$i]["amount_received"];
										break;
								case 'Debit_Card':
										$data['debit_card']     += (int) $someArray[$i]["amount_received"];
										break;
								case 'Paytm':
										$data['paytm']          += (int) $someArray[$i]["amount_received"];
										break;
								case 'Phonepe':
										$data['phone_pe']       += (int) $someArray[$i]["amount_received"];
										break;
								case 'Google_Pay':
										$data['google_pay']     += (int) $someArray[$i]["amount_received"];
										break;
								case 'Virtual_Wallet':
										$data['virtual_wallet'] += (int) $someArray[$i]["amount_received"];
										break;
								default:
										$data['others']         += (int) $someArray[$i]["amount_received"];
										break;
						}
				}
		}

		return $this->ModelHelper(true,false,'',$data);
	}
	public function GetLastMonthPackageSalesPaymentWiseData($where){
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
		mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
		SUM(mss_package_transaction_settlements.amount_received) AS 'Sum Amount'
		FROM 
			mss_package_transactions,
			mss_package_transaction_settlements,
			mss_customers
		WHERE
		date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND
		mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
		AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_transaction_settlement_id
		AND mss_package_transaction_settlements.settlement_way = 'Full Payment'
		AND mss_customers.customer_business_admin_id =  ".$this->db->escape($where['business_admin_id'])."
		AND mss_customers.customer_business_outlet_id =  ".$this->db->escape($where['business_outlet_id'])."
		GROUP BY mss_package_transaction_settlements.payment_mode";

		$query1 = $this->db->query($sql1);

		$package_full_payment = $query1->result_array();
			foreach ($package_full_payment as $k) {
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
					mss_package_transaction_settlements.payment_mode AS 'Payment Mode'
				FROM 
					mss_package_transactions, 
					mss_package_transaction_settlements, 
					mss_customers 
				WHERE 
					date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND
					mss_package_transactions.package_txn_customer_id = mss_customers.customer_id 
					AND mss_package_transactions.package_txn_id = mss_package_transaction_settlements.package_transaction_settlement_id
					AND mss_package_transaction_settlements.settlement_way = 'Split Payment'
					AND mss_customers.customer_business_admin_id =".$this->db->escape($where['business_admin_id'])."
					AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."
					";

		$query2 = $this->db->query($sql2);
		$package_split_payment = $query2->result_array();
		foreach ($package_split_payment as $k) {
				$str = $k["Payment Mode"];
				$someArray=array();
				$someArray = json_decode($str, true);										
				$len = count($someArray);
				for($i=0;$i<$len;$i++){
						switch ($someArray[$i]["payment_type"]) {
								case 'Cash':
										$data['cash']           += (int) $someArray[$i]["amount_received"];
										break;
								case 'Credit_Card':
										$data['credit_card']    += (int) $someArray[$i]["amount_received"];
										break;
								case 'Debit_Card':
										$data['debit_card']     += (int) $someArray[$i]["amount_received"];
										break;
								case 'Paytm':
										$data['paytm']          += (int) $someArray[$i]["amount_received"];
										break;
								case 'Phonepe':
										$data['phone_pe']       += (int) $someArray[$i]["amount_received"];
										break;
								case 'Google_Pay':
										$data['google_pay']     += (int) $someArray[$i]["amount_received"];
										break;
								case 'Virtual_Wallet':
										$data['virtual_wallet'] += (int) $someArray[$i]["amount_received"];
										break;
								default:
										$data['others']         += (int) $someArray[$i]["amount_received"];
										break;
						}
				}
		}

		return $this->ModelHelper(true,false,'',$data);
	}
	//trigger exist or not
	public function CheckTriggerExist($data){
        $sql="SELECT 
		* 
		FROM 
			mss_auto_engage 
		WHERE 
			mss_auto_engage.business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
			mss_auto_engage.business_outlet_id=".$this->db->escape($data['business_outlet_id'])." AND
			mss_auto_engage.trigger_type=".$this->db->escape($data['trigger_type'])." ";

			$query = $this->db->query($sql);
			if($query->num_rows() > 0){
				return $this->ModelHelper(false,true,'Trigger Already Exist.');
			}
			else{
				return $this->ModelHelper(true,false,'');   
			}
	}
	//Top Cards Data for due amount
	public function GetTodaysDueAmount($data){
		$sql="SELECT 
		SUM(mss_transactions.txn_pending_amount) AS 'due_amount'    
		FROM 
			mss_transactions,
			mss_customers
		WHERE 
			date(mss_transactions.txn_datetime)= date(now()) AND
			mss_transactions.txn_status=1 AND
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
			mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	public function GetTodaysPackageDueAmount($data){
		$sql="SELECT 
		SUM(mss_package_transactions.package_txn_pending_amount) AS 'package_due_amount'    
		FROM 
			mss_package_transactions,
			mss_customers
		WHERE 
			date(mss_package_transactions.datetime)= date(now()) AND
			mss_package_transactions.package_txn_customer_id=mss_customers.customer_id AND
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
			mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	public function GetPendingAmountReceived($data){
		$sql="SELECT 
						SUM(mss_pending_amount_tracker.pending_amount_submitted) AS 'pending_amount_received'    
					FROM 
						mss_pending_amount_tracker,
						mss_customers
					WHERE 
						date(mss_pending_amount_tracker.date_time)= CURRENT_DATE AND
						mss_pending_amount_tracker.customer_id=mss_customers.customer_id AND
						mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
						mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}

	public function GetMonthlyDueAmount($data){
		$sql="SELECT 
		SUM(mss_transactions.txn_pending_amount) AS 'monthly_due_amount'    
		FROM 
			mss_transactions,
				mss_customers
		WHERE 
			date(mss_transactions.txn_datetime) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
			mss_transactions.txn_status=1 AND
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
			mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	public function GetMonthlyPendingAmountReceived($data){
		$sql="SELECT 
						SUM(mss_pending_amount_tracker.pending_amount_submitted) AS 'monthly_pending_amount_received'    
					FROM 
						mss_pending_amount_tracker,
						mss_customers
					WHERE 
						date(mss_pending_amount_tracker.date_time) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND
						mss_pending_amount_tracker.customer_id=mss_customers.customer_id AND
						mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
						mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}

	public function GetLastMonthDueAmount($data){
		$sql="SELECT 
		SUM(mss_transactions.txn_pending_amount) AS 'last_month_due_amount'    
		FROM 
			mss_transactions,
			mss_customers
		WHERE 
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
			mss_transactions.txn_status=1 AND
			date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND 
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND	mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";
		
			$query = $this->db->query($sql);
		
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	public function GetlastMonthPendingAmountReceived($data){
		$sql="SELECT 
						SUM(mss_pending_amount_tracker.pending_amount_submitted) AS 'last_month_pending_amount_received'    
					FROM 
						mss_pending_amount_tracker,
						mss_customers
					WHERE 
						date(mss_pending_amount_tracker.date_time) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND
						mss_pending_amount_tracker.customer_id=mss_customers.customer_id AND
						mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
						mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//Loyalty wallet payment received
	public function GetLoyaltyAmountReceived($data){
		$sql="SELECT 
				SUM(mss_transaction_settlements.txn_settlement_amount_received) AS 'loyalty_wallet' 
			FROM
				mss_transaction_settlements,
				mss_transactions,
				mss_customers
			WHERE
				mss_transaction_settlements.txn_settlement_payment_mode='Loyalty_wallet' AND
				date(mss_transactions.txn_datetime)=CURRENT_DATE AND
				mss_transaction_settlements.txn_settlement_txn_id=mss_transactions.txn_id AND
				mss_transactions.txn_customer_id=mss_customers.customer_id AND
				mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
				mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//current month Loyalty points given
	public function GetLoyaltyPointsGiven($data){
		$sql="SELECT 
			SUM(mss_transactions.txn_loyalty_points) AS 'loyalty_points'    
			FROM 
				mss_transactions,
				mss_customers
			WHERE 
			date(mss_transactions.txn_datetime)= date(now()) AND
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
				mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
				mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	public function GetMonthlyLoyaltyAmountReceived($data){
		$sql="SELECT 
				SUM(mss_transaction_settlements.txn_settlement_amount_received) AS 'monthly_loyalty_wallet' 
			FROM
				mss_transaction_settlements,
				mss_transactions,
				mss_customers
			WHERE
				mss_transaction_settlements.txn_settlement_payment_mode='Loyalty_wallet' AND
				mss_transactions.txn_status=1 AND
				date(mss_transactions.txn_datetime)>= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND
				mss_transaction_settlements.txn_settlement_txn_id=mss_transactions.txn_id AND
				mss_transactions.txn_customer_id=mss_customers.customer_id AND
				mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
				mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//current month Loyalty points given
	public function GetMonthlyLoyaltyPointsGiven($data){
		$sql="SELECT 
			SUM(mss_transactions.txn_loyalty_points) AS 'monthly_loyalty_points'    
			FROM 
				mss_transactions,
				mss_customers
			WHERE 
			date(mss_transactions.txn_datetime) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
			mss_transactions.txn_status=1 AND
				mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
				mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}

	//last month loyalty points received
	public function GetLastMonthLoyaltyAmountReceived($data){
		$sql="SELECT 
				SUM(mss_transaction_settlements.txn_settlement_amount_received) AS 'last_month_loyalty_wallet' 
			FROM
				mss_transaction_settlements,
				mss_transactions,
				mss_customers
			WHERE
				mss_transaction_settlements.txn_settlement_payment_mode='Loyalty_wallet' AND
				mss_transactions.txn_status=1 AND
				date(mss_transactions.txn_datetime)BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND
				mss_transaction_settlements.txn_settlement_txn_id=mss_transactions.txn_id AND
				mss_transactions.txn_customer_id=mss_customers.customer_id AND
				mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
				mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//last month Loyalty points given
	public function GetLastMonthLoyaltyPointsGiven($data){
		$sql="SELECT 
			SUM(mss_transactions.txn_loyalty_points) AS 'last_month_loyalty_points'    
			FROM 
				mss_transactions,
				mss_customers
			WHERE 
			date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
			mss_transactions.txn_status=1 AND
				mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
				mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//
	public function GetMonthlySalesTillDate($data){
		$sql="SELECT 
			sum(mss_transaction_services.txn_service_discounted_price) AS 'sales_till_date'		
			FROM mss_transactions, 
			mss_employees,
			mss_services,
			mss_transaction_services			
			WHERE mss_transactions.txn_cashier=mss_employees.employee_id
			AND mss_transactions.txn_id= mss_transaction_services.txn_service_txn_id
			AND mss_transaction_services.txn_service_service_id= mss_services.service_id
			AND date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND (CURRENT_DATE - INTERVAL 1 DAY)
			AND mss_services.inventory_type_id =0		 
			AND mss_transactions.txn_status=1
			AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."  ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	
	//Monthly Product Sales
	public function GetMonthlyProductSalesTillDate($data){
		$sql="SELECT sum(mss_transactions.txn_value) AS 'product_sales_till_date'
		FROM
		mss_transactions, 
		mss_employees,
		mss_transaction_services,
		mss_services
		WHERE
		mss_transactions.txn_cashier=mss_employees.employee_id
		AND mss_transaction_services.txn_service_txn_id=mss_transactions.txn_id
		AND mss_transaction_services.txn_service_service_id=mss_services.service_id
		AND mss_services.inventory_type_id IN (1,2)
		AND mss_transactions.txn_status=1
		and date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND (CURRENT_DATE - INTERVAL 1 DAY)
		AND mss_transaction_services.txn_service_txn_id   
		AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])." ";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	
	
	//Monthly Package SalesM
	public function PackageSalesTillDate($data){
		$sql = "SELECT sum(mss_package_transactions.package_txn_value) AS package_sales
		 FROM mss_package_transactions , 
		 mss_employees 		
        WHERE mss_package_transactions.package_txn_cashier = mss_employees.employee_id
        AND date(mss_package_transactions.datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),INTERVAL 1 DAY),INTERVAL -1 MONTH) AND (CURRENT_DATE - INTERVAL 1 DAY)
        AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])." ";

		$query = $this->db->query($sql);
	
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		} 
	}
	//Last Month Package Sales
	public function PackageSalesForLastMonth($data){
		$sql = "SELECT SUM(mss_package_transactions.package_txn_value) AS package_sales
		FROM mss_package_transactions , mss_employees 		
        WHERE mss_package_transactions.package_txn_cashier = mss_employees.employee_id
        AND date(mss_package_transactions.datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH)
        AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";

		$query = $this->db->query($sql);
	
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		} 
	}
	//Monthly TopCards data
	public function GetTopCardsDataMonthly($data){
		switch ($data['type']) {
				case 'monthly_sales':
				$sql = "SELECT 
					SUM(mss_transactions.txn_value) AS 'monthly_sales'
				FROM
					mss_transactions,
					mss_transaction_settlements,
					mss_customers
				WHERE
				mss_transactions.txn_id=mss_transaction_settlements.txn_settlement_txn_id
				AND
				mss_transactions.txn_customer_id = mss_customers.customer_id
				AND date(mss_transactions.txn_datetime) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
				AND mss_transactions.txn_status=1
				AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
				AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."";
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
				mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
				AND mss_customers.customer_business_outlet_id =".$this->db->escape($data['business_outlet_id'])." 
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
														AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
														AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
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
												AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])." 
												AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
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
														AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
														AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." 
														AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())
												GROUP BY
														mss_transactions.txn_customer_id
												HAVING
														cnt > 1
												) T1";
						break;
				case 'monthly_expenses':
						$sql = " SELECT 
												SUM(mss_expenses.amount) AS 'monthly_expenses'
										FROM 
												mss_expenses,
												mss_expense_types
										WHERE
												mss_expenses.expense_type_id = mss_expense_types.expense_type_id
												AND mss_expenses.expense_date >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) 
												AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
												AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."";
						break;
								default:
										return $this->ModelHelper(false,true,"Wrong choice!");
										break;
						}

						
					$query = $this->db->query($sql);
							
							if($query){
									return $this->ModelHelper(true,false,'',$query->row_array());
							}
							else{
									return $this->ModelHelper(false,true,"DB error!");   
							}
	} 
	//Monthly 
	public function GetMonthlySalesPaymentWiseData($where){
		$data = array(
				'monthly_cash'           => 0,
				'monthly_credit_card'    => 0,
				'monthly_debit_card'     => 0,
				'monthly_google_pay'     => 0,
				'monthly_phone_pe'       => 0,
				'monthly_paytm'          => 0,
				'monthly_virtual_wallet' => 0,
				'monthly_others'         => 0
		 );

		//Calculate Full Payment Sales for Today
		$sql1 = "SELECT
				mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode',
				SUM(mss_transaction_settlements.txn_settlement_amount_received-mss_transaction_settlements.txn_settlement_balance_paid) AS 'Sum Amount'
			FROM 
				mss_transactions,
				mss_transaction_settlements,
				mss_customers
			WHERE
			  mss_transactions.txn_status=1
			AND date(mss_transactions.txn_datetime) >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
			AND mss_transactions.txn_customer_id = mss_customers.customer_id
			AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
			AND mss_transaction_settlements.txn_settlement_way = 'Full Payment'
			AND mss_customers.customer_business_admin_id = ".$this->db->escape($where['business_admin_id'])."
			AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."
		
			GROUP BY mss_transaction_settlements.txn_settlement_payment_mode";

		$query1 = $this->db->query($sql1);

		$data_full_payment = $query1->result_array();
			foreach ($data_full_payment as $k) {
					switch ($k['Payment Mode']) {
							case 'Cash':
									$data['monthly_cash']           += (int) $k['Sum Amount'];
									break;
							case 'Credit_Card':
									$data['monthly_credit_card']    += (int) $k['Sum Amount'];
									break;
							case 'Debit_Card':
									$data['monthly_debit_card']     += (int) $k['Sum Amount'];
									break;
							case 'Paytm':
									$data['monthly_paytm']          += (int) $k['Sum Amount'];
									break;
							case 'Phonepe':
									$data['monthly_phone_pe']       += (int) $k['Sum Amount'];
									break;
							case 'Google_Pay':
									$data['monthly_google_pay']     += (int) $k['Sum Amount'];
									break;
							case 'Virtual_Wallet':
									$data['monthly_virtual_wallet'] += (int) $k['Sum Amount'];
									break;
							default:
									$data['monthly_others']         += (int) $k['Sum Amount'];
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
								AND date(mss_transactions.txn_datetime)  >= date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
								AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
								AND mss_transactions.txn_status=1
								AND mss_transaction_settlements.txn_settlement_way = 'Split Payment'
								AND mss_customers.customer_business_admin_id = ".$this->db->escape($where['business_admin_id'])."
								AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."";
							

		$query2 = $this->db->query($sql2);

		$data_split_payment = $query2->result_array();

		foreach ($data_split_payment as $k) {
				$str = $k["Payment Mode"];
								
				$someArray = json_decode($str, true);
																		
				$len = count($someArray);
								
				for($j=0;$j<$len;$j++){
						switch ($someArray[$j]["payment_type"]) {
								case 'Cash':
										$data['monthly_cash']           += (int) $someArray[$j]["amount_received"];
										break;
								case 'Credit_Card':
										$data['monthly_credit_card']    += (int) $someArray[$j]["amount_received"];
										break;
								case 'Debit_Card':
										$data['monthly_debit_card']     += (int) $someArray[$j]["amount_received"];
										break;
								case 'Paytm':
										$data['monthly_paytm']          += (int) $someArray[$j]["amount_received"];
										break;
								case 'Phonepe':
										$data['monthly_phone_pe']       += (int) $someArray[$j]["amount_received"];
										break;
								case 'Google_Pay':
										$data['monthly_google_pay']     += (int) $someArray[$j]["amount_received"];
										break;
								case 'Virtual_Wallet':
										$data['monthly_virtual_wallet'] += (int) $someArray[$j]["amount_received"];
										break;
								default:
										$data['monthly_others']         += (int) $someArray[$j]["amount_received"];
										break;
						}
				}
		}

		return $this->ModelHelper(true,false,'',$data);
	}
	
	//last month data
	public function GetTopCardsDataForLastMonth($data){
		switch ($data['type']) {
				case 'last_month_sales':
					$sql = "SELECT sum(mss_transactions.txn_value) AS 'last_month_sales'
					FROM
					mss_transactions, mss_employees,mss_transaction_services,mss_services
					WHERE
                    mss_transactions.txn_cashier=mss_employees.employee_id
                    AND mss_transaction_services.txn_service_txn_id=mss_transactions.txn_id
                    AND mss_transaction_services.txn_service_service_id=mss_services.service_id
                    AND mss_services.inventory_type_id =0  
                	AND mss_transactions.txn_status=1
                    and date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND (CURRENT_DATE - INTERVAL 1 MONTH)
                    AND mss_transaction_services.txn_service_txn_id
					AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";
				//
				
				//
					break;
					case 'last_month_product_sales':
						$sql = "SELECT sum(mss_transactions.txn_value) AS 'last_month_product_sales'
						FROM
						mss_transactions, mss_employees,mss_transaction_services,mss_services
						WHERE
						mss_transactions.txn_cashier=mss_employees.employee_id
						AND mss_transaction_services.txn_service_txn_id=mss_transactions.txn_id
						AND mss_transaction_services.txn_service_service_id=mss_services.service_id
						AND mss_services.inventory_type_id IN(1,2)  
						AND mss_transactions.txn_status=1
						and date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH) AND (CURRENT_DATE - INTERVAL 1 MONTH)
						AND mss_transaction_services.txn_service_txn_id
						AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";
					//
					
					//
							break;
				case 'last_month_expense':
						$sql = "SELECT 
												SUM(mss_expenses.amount) AS 'last_month_expense'
										FROM 
												mss_expenses,
												mss_expense_types
										WHERE
												mss_expenses.expense_type_id = mss_expense_types.expense_type_id
												AND mss_expenses.expense_date BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH)
												AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
												AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."";
						break;
								default:
										return $this->ModelHelper(false,true,"Wrong choice!");
										break;
						}
					$query = $this->db->query($sql);
							
							if($query){
									return $this->ModelHelper(true,false,'',$query->row_array());
							}
							else{
									return $this->ModelHelper(false,true,"DB error!");   
							}
	}  
	//last month payment wise data
	public function GetLastMonthSalesPaymentWiseData($where){
		$data = array(
				'last_month_cash'           => 0,
				'last_month_credit_card'    => 0,
				'last_month_debit_card'     => 0,
				'last_month_google_pay'     => 0,
				'last_month_phone_pe'       => 0,
				'last_month_paytm'          => 0,
				'last_month_virtual_wallet' => 0,
				'last_month_others'         => 0
		 );

		//Calculate Full Payment Sales for Today
		$sql1 = "SELECT
				mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode',
				SUM(mss_transaction_settlements.txn_settlement_amount_received-mss_transaction_settlements.txn_settlement_balance_paid) AS 'Sum Amount'
			FROM 
				mss_transactions,
				mss_transaction_settlements,
				mss_customers
			WHERE
			  mss_transactions.txn_status=1
			AND date(mss_transactions.txn_datetime) BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH)
			AND mss_transactions.txn_customer_id = mss_customers.customer_id
			AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
			AND mss_transaction_settlements.txn_settlement_way = 'Full Payment'
			AND mss_customers.customer_business_admin_id = ".$this->db->escape($where['business_admin_id'])."
			AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."
		
			GROUP BY mss_transaction_settlements.txn_settlement_payment_mode";

		$query1 = $this->db->query($sql1);

		$data_full_payment = $query1->result_array();
			foreach ($data_full_payment as $k) {
					switch ($k['Payment Mode']) {
							case 'Cash':
									$data['last_month_cash']           += (int) $k['Sum Amount'];
									break;
							case 'Credit_Card':
									$data['last_month_credit_card']    += (int) $k['Sum Amount'];
									break;
							case 'Debit_Card':
									$data['last_month_debit_card']     += (int) $k['Sum Amount'];
									break;
							case 'Paytm':
									$data['last_month_paytm']          += (int) $k['Sum Amount'];
									break;
							case 'Phonepe':
									$data['last_month_phone_pe']       += (int) $k['Sum Amount'];
									break;
							case 'Google_Pay':
									$data['last_month_google_pay']     += (int) $k['Sum Amount'];
									break;
							case 'Virtual_Wallet':
									$data['last_month_virtual_wallet'] += (int) $k['Sum Amount'];
									break;
							default:
									$data['last_month_others']         += (int) $k['Sum Amount'];
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
								AND date(mss_transactions.txn_datetime)  BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01') AND ((date(now())) - INTERVAL 1 MONTH)
								AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
								AND mss_transactions.txn_status=1
								AND mss_transaction_settlements.txn_settlement_way = 'Split Payment'
								AND mss_customers.customer_business_admin_id = ".$this->db->escape($where['business_admin_id'])."
								AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."";
							

		$query2 = $this->db->query($sql2);

		$data_split_payment = $query2->result_array();

		foreach ($data_split_payment as $k) {
				$str = $k["Payment Mode"];
								
				$someArray = json_decode($str, true);
																		
				$len = count($someArray);
								
				for($j=0;$j<$len;$j++){
						switch ($someArray[$j]["payment_type"]) {
								case 'Cash':
										$data['last_month_cash']           += (int) $someArray[$j]["amount_received"];
										break;
								case 'Credit_Card':
										$data['last_month_credit_card']    += (int) $someArray[$j]["amount_received"];
										break;
								case 'Debit_Card':
										$data['last_month_debit_card']     += (int) $someArray[$j]["amount_received"];
										break;
								case 'Paytm':
										$data['last_month_paytm']          += (int) $someArray[$j]["amount_received"];
										break;
								case 'Phonepe':
										$data['last_month_phone_pe']       += (int) $someArray[$j]["amount_received"];
										break;
								case 'Google_Pay':
										$data['last_month_google_pay']     += (int) $someArray[$j]["amount_received"];
										break;
								case 'Virtual_Wallet':
										$data['last_month_virtual_wallet'] += (int) $someArray[$j]["amount_received"];
										break;
								default:
										$data['last_month_others']         += (int) $someArray[$j]["amount_received"];
										break;
						}
				}
		}

		return $this->ModelHelper(true,false,'',$data);
	}  
	//Total Due Amout till date-1
	public function GetTotalDueAmount($data){
		$sql="SELECT 
		SUM(mss_transactions.txn_pending_amount) AS 'total_due_amount'    
		FROM 
		mss_transactions,
			mss_customers
		WHERE 
			date(mss_transactions.txn_datetime) <= DATE_SUB(CURRENT_DATE,INTERVAL 1 DAY) AND
			mss_transactions.txn_customer_id=mss_customers.customer_id AND
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
			mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//TotalV Virtual Wallet Balance
	public function GetTotalVirtualWalletBalance($data){
		$sql="SELECT 
			SUM(mss_customers.customer_virtual_wallet) AS 'total_virtual_wallet_balance'
		FROM
			mss_customers
		WHERE
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
			mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	public function TodayVirtualWalletSales($data){
		$sql="SELECT 
		SUM(mss_package_transaction_settlements.amount_received) AS 'wallet_sales'
		FROM
		mss_package_transactions,
		mss_package_transaction_settlements,
		mss_customer_packages,
		mss_transaction_package_details,
		mss_customers
		WHERE
		
		mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
		mss_customer_packages.salon_package_type='Wallet' AND
		mss_customer_packages.customer_id=mss_package_transactions.package_txn_customer_id AND
		mss_customer_packages.salon_package_id= mss_transaction_package_details.salon_package_id AND
		mss_package_transactions.package_txn_customer_id = mss_customers.customer_id AND
		date(mss_package_transactions.datetime) = date(now()) AND 
		mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id AND
		mss_customers.customer_wallet_expiry_date > CURRENT_DATE AND
		mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND
		mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	
	
	//Update transaction status
	public function UpdateTransactionService($data){
		$res = $this->DetailsById($data['txn_service_service_id'],'mss_services','service_id');
		$tax=round(($res['res_arr']['service_price_inr']*$res['res_arr']['service_gst_percentage'])/100);
		
		$sql="UPDATE 
				mss_transaction_services, 
				mss_transactions, mss_transaction_settlements 
				SET mss_transaction_services.txn_service_status=0,
				mss_transactions.txn_total_tax=mss_transactions.txn_total_tax-$tax,	
				mss_transaction_settlements.txn_settlement_amount_received=(mss_transaction_settlements.txn_settlement_amount_received- ".$this->db->escape($data['txn_service_discounted_price'])."),	mss_transactions.txn_value=(mss_transactions.txn_value- ".$this->db->escape($data['txn_service_discounted_price'])."),
				mss_transaction_settlements.txn_settlement_amount_received=(mss_transaction_settlements.txn_settlement_amount_received-".$this->db->escape($data['txn_service_discounted_price'])."),
				mss_transaction_settlements.txn_settlement_reversed=".$this->db->escape($data['txn_service_discounted_price'])."
				WHERE mss_transaction_services.txn_service_service_id=".$this->db->escape($data['txn_service_service_id'])." AND
				mss_transactions.txn_id=".$this->db->escape($data['txn_id'])." AND 
				mss_transaction_settlements.txn_settlement_txn_id=".$this->db->escape($data['txn_id'])." ";	
			$query = $this->db->query($sql);
			
			$all_service_status=$this->MultiWhereSelect('mss_transaction_services',array('txn_service_txn_id'=>$data['txn_id'],'txn_service_status'=>1));
			if($all_service_status['res_arr']=="" || $all_service_status['res_arr']==null){
				$q="UPDATE mss_transactions SET txn_status=0  WHERE mss_transactions.txn_id=".$this->db->escape($data['txn_id'])." ";
	
				$this->db->query($q);
			}
			if($query){
				return $this->ModelHelper(true,false,'');
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//
	public function GetFullTransactionDetail($data){
		$sql="SELECT mss_transactions.txn_id,
				date(mss_transactions.txn_datetime) AS 'date',
				mss_services.service_name,
				mss_customers.customer_name,
                mss_customers.customer_mobile,
				mss_transactions.txn_customer_id,
				mss_transactions.txn_value,
				mss_transaction_services.txn_service_id,
				ROUND(mss_services.service_price_inr+(mss_services.service_price_inr*mss_services.service_gst_percentage/100)) AS 'mrp',
				mss_transaction_services.txn_service_discount_percentage AS 'disc1',
        		mss_transaction_services.txn_service_discount_absolute AS 'disc2',
				mss_transaction_services.txn_service_service_id AS 'service_id',
				mss_transaction_services.txn_service_quantity,
				mss_transaction_services.txn_service_discounted_price,
				mss_transaction_services.txn_service_expert_id,
				mss_employees.employee_first_name AS  'expert',
				mss_transaction_settlements.txn_settlement_payment_mode AS 'payment_mode',
				mss_transaction_settlements.txn_settlement_amount_received
			FROM
				mss_transactions,
				mss_transaction_settlements,
				mss_transaction_services,
				mss_services,
				mss_customers,
				mss_employees

			WHERE 
				mss_transaction_services.txn_service_status=1 AND
				mss_transactions.txn_customer_id= mss_customers.customer_id AND 
				mss_employees.employee_id=mss_transaction_services.txn_service_expert_id AND
				mss_services.service_id= mss_transaction_services.txn_service_service_id AND
				mss_transaction_settlements.txn_settlement_txn_id=mss_transactions.txn_id AND
				mss_transaction_services.txn_service_txn_id= mss_transactions.txn_id AND
			mss_transactions.txn_id=".$this->db->escape($data)." GROUP BY mss_transaction_services.txn_service_id";

			$query = $this->db->query($sql);
			
			if($query->num_rows()>0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			} 
	}
	//EMSS 
public function commission_details()
    {
        $query=$this->db->query("SELECT * FROM mss_emss_commision,mss_employees where mss_employees.employee_id=mss_emss_commision.emp_id
        AND mss_emss_commision.bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']." AND mss_employees.employee_is_active=1
         group by mss_emss_commision.names,mss_emss_commision.emp_id order by comm_id desc
        ");
        // $query=$this->db->query("SELECT * FROM mss_emss_commision,mss_employees where mss_employees.employee_id=mss_emss_commision.emp_id
        // AND mss_emss_commision.bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']." AND mss_employees.employee_is_active=1
        // order by comm_id desc
        // ");
        return $result=$query->result_array();
    }
    
    public function commission_edit($data)
    {
        $query=$this->db->query("SELECT * FROM mss_emss_commision where mss_emss_commision.comm_id=".$this->db->escape($data['comm_id'])."
        ");
        // $query=$this->db->query("SELECT DISTINCT set_target1,set_target2,commision,base_value FROM mss_emss_commision where 
        // mss_emss_commision.months=".$this->db->escape($data['month'])."
        // AND mss_emss_commision.names=".$this->db->escape($data['comm_name'])." AND mss_emss_commision.bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."		
        // ");
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            }
    }
    public function DeleteCommission($data){
        // print_r($data);
        // exit;
        $query=$this->db->query("DELETE FROM mss_emss_commision WHERE names=".$this->db->escape($data['names'])." AND emp_id=".$this->db->escape($data['emp_id'])." AND  months=".$this->db->escape($data['months'])."
        ");
        if($query){
            return $this->ModelHelper(true,false,'');
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            }
    }

    
     public function GetCommissionDetails($data)
    {
        $query=$this->db->query("SELECT DISTINCT set_target1,set_target2,commision,base_value FROM mss_emss_commision where 
        mss_emss_commision.months=".$this->db->escape($data['month'])."
        AND mss_emss_commision.names=".$this->db->escape($data['comm_name'])." AND mss_emss_commision.bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."	 AND mss_emss_commision.emp_id=".$this->db->escape($data['expert_id'])."	
        ");
        $result=$query->result_array();
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }


    public function commission_details_All()
    {
        $query=$this->db->query("SELECT *,mss_business_outlets.business_outlet_name FROM mss_emss_commision_all,mss_business_outlets where mss_emss_commision_all.bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ");
        return $result=$query->result_array();
    }

     public function NewCommission($data)
    {
        $this->db->select("employee_id,employee_first_name,employee_last_name,employee_business_outlet,employee_gross_salary");
        $this->db->where('employee_business_outlet',$this->session->userdata['outlets']['current_outlet']);
        $this->db->where('employee_is_active',1);
        $query=$this->db->get("mss_employees");
        return  $result=$query->result_array();
    }

    //attendance report

    public function GetAttendanceReport($data)
    {
        // $this->PrintArray($data);
        // exit;
        $sql = "SELECT mss_employees.employee_id AS 'ID',mss_employees.employee_first_name as 'FIRST NAME',mss_employees.employee_last_name AS 'LAST NAME',mss_employees.employee_mobile AS 'MOBILE',mss_emss_attendance.attendance_date AS 'DATE',
        TIME_FORMAT(mss_emss_attendance.in_time,'%H:%i:%s') as 'IN-TIME',
        TIME_FORMAT(mss_emss_attendance.out_time,'%H:%i:%s') as 'OUT-TIME',
        mss_emss_attendance.working_hours as 'WORKING-MINUTES',
        mss_emss_attendance.over_time as 'OVER-TIME'
        from mss_employees,mss_emss_attendance 
        where 
        MONTH(mss_emss_attendance.attendance_date)=MONTH(date(now()))
        AND YEAR(mss_emss_attendance.attendance_date)=year(date(now()))
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
        AND mss_emss_attendance.employee_business_admin_id=".$this->db->escape($data['business_admin_id'])."
        AND date(mss_emss_attendance.attendance_date) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        order by mss_emss_attendance.attendance_date";

        $query = $this->db->query($sql);
        // $this->sss

        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        }

    }
    // for basic salary(basevalue or kpi) of expertt
   public function CommissionGetSalary($commission){
        if($commission['base_kpi'] == 'gross_salary')
        {
          return $this->CommissionBasicSalary($commission);  
        }
        elseif($commission['base_kpi'] == 'Avg_sales_mrp')
        {
           return $this ->CommissionTotalSales($commission); 
        }
        elseif($commission['base_kpi']=='Avg_total_disc_price')
        {
            return $this ->CommissionServiceSales($commission);   
        }

    }
    // avg total sales
    public function CommissionBasicSalary($commission)
    {
      
        $sql="SELECT employee_gross_salary FROM mss_employees WHERE employee_id=".$this->db->escape($commission['expert_id']);
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
    // 3 month avg total sales
    public function CommissionTotalSales($commission)
    {
        $sql="SELECT 
        SUM(mss_transaction_services.txn_service_discounted_price)/3 AS 'total'
    FROM
        mss_transactions,
        mss_auto_engage,
        mss_transaction_services,
        mss_employees,
        mss_services,
        mss_business_outlets
    WHERE
        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
        AND mss_business_outlets.business_outlet_status=1
        AND mss_employees.employee_business_outlet=mss_business_outlets.business_outlet_id
        AND mss_auto_engage.business_outlet_id=mss_employees.employee_business_outlet
        AND	mss_employees.employee_id=".$this->db->escape($commission['expert_id'])."
        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
        AND mss_transaction_services.txn_service_service_id = mss_services.service_id              
        AND date(mss_transactions.txn_datetime) BETWEEN (last_day(CURRENT_DATE) + interval 1 day - interval 4 month) AND LAST_DAY(now() - INTERVAL 1 MONTH)";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
    // 3 month avg service sales
    public function CommissionServiceSales($commission)
    {
        $sql="SELECT 
        SUM(mss_transactions.txn_value)/3 AS 'Service'
   FROM
        mss_transactions,
        mss_transaction_services,
        mss_employees,
        mss_customers,
        mss_categories,
        mss_sub_categories,
        mss_services
   WHERE
        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
        AND mss_employees.employee_id=".$this->db->escape($commission['expert_id'])."
        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN (last_day(CURRENT_DATE) + interval 1 day - interval 4 month) AND LAST_DAY(now() - INTERVAL 1 MONTH)";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
// bBase value for commission
   public function CommissionBaseValue($data)
  {
        // $this->PrintArray($data);
		// exit;
    
        if($data[0]=='Total Sales' || $data[0]=='Total Sales Discount' ||  $data[0]=='Total Sales Net Price')
        {
        return $this->CommissionTotalSalesB($data); 
        }
        elseif($data[0]=='ServiceSales' || $data[0] == 'Total Sales MRP')
        {
            return $this->CommissionServiceValueB($data);
        }
    
  }

//   Total Sales amt for commisiion
  public function CommissionTotalSalesB($commission)
  {
      $sql="SELECT 

      SUM(mss_transaction_services.txn_service_discounted_price) AS 'total'
  FROM
      mss_transactions,
      mss_auto_engage,
      mss_transaction_services,
      mss_employees,
      mss_services,
      mss_business_outlets
  WHERE
      mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
      AND mss_business_outlets.business_outlet_status=1
      AND mss_employees.employee_business_outlet=mss_business_outlets.business_outlet_id
      AND mss_auto_engage.business_outlet_id=mss_employees.employee_business_outlet
      AND	mss_employees.employee_id=".$this->db->escape($commission[1])."
      AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
      AND mss_transaction_services.txn_service_service_id = mss_services.service_id              
      AND date(mss_transactions.txn_datetime) >= DATE_ADD(NOW(),INTERVAL -90 DAY)";
      $query = $this->db->query($sql);
      

      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      }
  }
  //
  public function CommissionServiceValueB($commission)
  {
      $sql="SELECT 
                   
      SUM(mss_transactions.txn_value) AS 'Service'
 FROM
      mss_transactions,
      mss_transaction_services,
      mss_employees,
      mss_customers,
      mss_categories,
      mss_sub_categories,
      mss_services
 WHERE
      mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
      AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
      AND mss_employees.employee_id=".$this->db->escape($commission[1])."
      AND mss_transaction_services.txn_service_service_id = mss_services.service_id
      AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
      AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
      AND mss_transactions.txn_customer_id = mss_customers.customer_id
      AND date(mss_transactions.txn_datetime) >= DATE_ADD(NOW(),INTERVAL -90 DAY)";
      $query = $this->db->query($sql);
      

      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      } 
  }




  public function EmssAttendanceReport($data){

    // $this->PrintArray($data);
    // exit;
    if($data['group_name'] == 'days_present'){
        return $this->GetPresentAttendance($data);
    }
    elseif ($data['group_name'] == 'days_absent') {
        return $this->GetAbsentAttendance($data);
    } 
   elseif ($data['group_name'] == 'half_days') {
        return $this->GetHalfDayAttendance($data);
    } 
    elseif ($data['group_name'] == 'percentage_attendance') {
        return $this->GetPercentAttendance($data); 
    } 
    elseif ($data['group_name'] == 'emp_days_present') {
        return $this->GetEmpPresentAttendance($data); 
    } 
    elseif ($data['group_name'] == 'emp_days_absent') {
        return $this->GetEmpAbsentAttendance($data); 
    } 
    elseif ($data['group_name'] == 'emp_half_days') {
        return $this->GetEmpHalfDayAttendance($data); 
    } 
    elseif ($data['group_name'] == 'emp_percentage_attendance') {
        return $this->GetEmpPercentAttendance($data); 
    } 
    elseif($data['group_name']=='attendance')
    {
        return $this->GetAttendanceAll($data);
    }
    

    // emp-month
    elseif ($data['group_name'] == 'month_days_present') {
        return $this->GetMonthPresentAttendance($data); 
    } 
    elseif ($data['group_name'] == 'month_days_upsent') {
        return $this->GetMonthAbsentAttendance($data); 
    } 
    elseif ($data['group_name'] == 'month_half_days') {
        return $this->GetMonthHalfDayAttendance($data); 
    } 
    elseif ($data['group_name'] == 'month_percentage_attendance') {
        return $this->GetMonthPercentAttendance($data); 
    } 
    // end

    else{
        $this->ModelHelper(false,true,"No such report exists!");
    }
}

    public function GetPresentAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(attendance_id) as 'Present' FROM mss_emss_attendance,mss_employees 
        where mss_emss_attendance.working_hours >= 0
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
         AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function GetHalfDayAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(attendance_id) as 'Half_Day' FROM mss_emss_attendance,mss_employees,mss_business_outlets 
        where mss_emss_attendance.working_hours < 300
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
         AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function GetAbsentAttendance($data)
    {
        // print_r($data);
        // exit;
        $sql="SELECT 
        mss_emss_attendance.employee_id,
        mss_employees.employee_first_name,
        mss_employees.employee_last_name,
        ".$this->db->escape($data['holiday'])." as 'holidays',
        count(mss_emss_attendance.attendance_id) as 'Present',
        DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1 as 'Difference',	
        ((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff))-".$this->db->escape($data['holiday'])." as 'Total_Days',
        JSON_LENGTH(mss_employees.employee_weekoff) as 'weekoff',
        
        
        (((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff))-".$this->db->escape($data['holiday']).")-count(mss_emss_attendance.attendance_id) as 'Absent'
        
        FROM
        mss_employees,mss_emss_attendance
        where 
        mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."        
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
    public function EmssGetHolidays($data)
    {
   
        $sql="SELECT count(holiday_id) as 'holiday'
        FROM mss_emss_holidays 
        WHERE 
            month(holiday_date)=month(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
            AND Year(holiday_date)=Year(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
        AND holiday_business_admin_id=".$this->db->escape($data['business_admin_id'])."";
        $query = $this->db->query($sql);
        // print_r($query);
        // exit;
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function EmssGetHolidaysReport($data)
    {
   
        $sql="SELECT count(holiday_id) as 'holiday'
        FROM mss_emss_holidays 
        WHERE 
            holiday_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
             
        AND holiday_business_admin_id=".$this->db->escape($data['business_admin_id'])."";
        $query = $this->db->query($sql);
  
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetPercentAttendance($data)
    {
       
        $sql="SELECT
        mss_emss_attendance.employee_id,
        mss_employees.employee_first_name,
        mss_employees.employee_last_name,
        count(mss_emss_attendance.attendance_id) as 'Days_Present',
        (DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1) as 'diff',	
        ((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff))-".$this->db->escape($data['holiday'])." as 'Total_Days',
        JSON_LENGTH(mss_employees.employee_weekoff) as 'weekoff',
        ".$this->db->escape($data['holiday'])." as 'Holidays', 
        
        round((count(mss_emss_attendance.attendance_id))/((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff)-".$this->db->escape($data['holiday']).")*100,1) as 'Percent_Present'
        
        FROM mss_emss_attendance,mss_employees
               
        where 
               
                mss_emss_attendance.working_hours >= 0
                AND mss_employees.employee_id=mss_emss_attendance.employee_id
                AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
                AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
               
        group BY 
                mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
  
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // Employee wise monthly report
    // Present
    public function GetEmpPresentAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(attendance_id) as 'present' FROM mss_emss_attendance,mss_employees 
        where mss_emss_attendance.working_hours >= 0
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND	mss_emss_attendance.employee_id=".$this->db->escape($data['expert_id'])."
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
         AND month(mss_emss_attendance.attendance_date)=month(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
         AND Year(mss_emss_attendance.attendance_date)=Year(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
         group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    //Emp Absent
    public function GetEmpAbsentAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(mss_emss_attendance.attendance_id) as 'Days Present',DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).") as 'Total Days',((count(mss_emss_attendance.attendance_id))/(DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date'])."))*100) as 'Percent_Present' FROM mss_emss_attendance,mss_employees 
        where 
        mss_emss_attendance.working_hours >= 0
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
        AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // Emp Half Day
    public function GetEmpHalfDayAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(attendance_id) as 'HalfDay' FROM mss_emss_attendance,mss_employees
        where mss_emss_attendance.working_hours < (mss_employees.working_hours*60)
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
         AND month(mss_emss_attendance.attendance_date)=month(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
         AND Year(mss_emss_attendance.attendance_date)=Year(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
   
    // Emp Percent Attendance
    public function GetEmpPercentAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,
        mss_employees.employee_first_name,
        mss_employees.employee_last_name,
        count(mss_emss_attendance.attendance_id) as 'Days Present',
        count(mss_emss_holidays.holiday_id) as 'holidays',
        (DAY(LAST_DAY(CONCAT(".$this->db->escape($data['month_name'])."),'-','00')))-(count(mss_emss_holidays.holiday_id)) as 'total days',
       (count(mss_emss_attendance.attendance_id)/round(DAY(CONCAT(".$this->db->escape($data['month_name'])."),'-','00')))-(count(mss_emss_holidays.holiday_id)))*100,2) as 'Attendance Percent'
        FROM mss_emss_attendance,mss_employees,mss_emss_holidays
        where 
        mss_emss_attendance.working_hours >= 0
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_id=".$this->db->escape($data['expert_id'])."
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
        ANd month(mss_emss_holidays.holiday_date)=month(CONCAT((".$this->db->escape($data['month_name'])."),'-','00')
        ANd YEAR(mss_emss_holidays.holiday_date)=YEAR(CONCAT((".$this->db->escape($data['month_name'])."),'-','00')
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

// emp 2 month wise
    public function GetMonthPresentAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(mss_emss_attendance.attendance_id) as 'present',DATE_FORMAT(mss_emss_attendance.attendance_date,'%M') as 'Month', 
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%Y') as 'Year' FROM mss_emss_attendance,mss_employees
         WHERE mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        group by MONTH(mss_emss_attendance.attendance_date),mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    //Month wise employee-wise attendance report
    public function GetMonthAbsentAttendance($data)
    {
        // print_r($data);
        // exit;
        $sql="SELECT 
        mss_emss_attendance.employee_id,
        mss_employees.employee_first_name,
        mss_employees.employee_last_name,
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%M') as 'Month',
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%Y') as 'Year',
        count(mss_emss_attendance.attendance_id) as 'Present',
        (DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1) AS 'differance',	
        ((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff))-".$this->db->escape($data['holiday'])." as 'Total_Days',
        JSON_LENGTH(mss_employees.employee_weekoff) as 'weekoff',
       (((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff))-".$this->db->escape($data['holiday']).")-count(mss_emss_attendance.attendance_id) AS 'Absent'
        FROM
        mss_employees,mss_emss_attendance
        WHERE 
             mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
        AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
        group BY mss_emss_attendance.employee_id,month(mss_emss_attendance.attendance_date)";

        $query = $this->db->query($sql);
        // print_r($query);
        // exit;
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
// monthwise em-p wise (2597) 
    public function GetMonthHalfDayAttendance($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,
        mss_employees.employee_first_name,
        mss_employees.employee_last_name,
        count(mss_emss_attendance.attendance_id) as 'Half Days',
        DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1 as 'Differance',
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%M') as 'Month', 
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%Y') as 'Year'
        
        
        
        FROM mss_emss_attendance,mss_employees,mss_business_outlets 
                where 
                mss_emss_attendance.working_hours < (mss_business_outlets.working_hours*60)
                AND mss_employees.employee_id=mss_emss_attendance.employee_id
                AND mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['business_outlet_id'])."
                AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
                group BY mss_emss_attendance.employee_id,month(mss_emss_attendance.attendance_date)";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
    public function GetMonthPercentAttendance($data)
    {
        $sql="SELECT
        mss_emss_attendance.employee_id,
        mss_employees.employee_first_name,
        mss_employees.employee_last_name,
        count(mss_emss_attendance.attendance_id) as 'Days_Present',
        (DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1) as 'Difference',	
        ((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff))-".$this->db->escape($data['holiday'])." as 'Total_Days',
        JSON_LENGTH(mss_employees.employee_weekoff) as 'weekoff',
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%M') as 'MONTH',
        DATE_FORMAT(mss_emss_attendance.attendance_date,'%Y') as 'YEAR',
        round((count(mss_emss_attendance.attendance_id))/((DATEDIFF(".$this->db->escape($data['to_date']).",".$this->db->escape($data['from_date']).")+1)-JSON_LENGTH(mss_employees.employee_weekoff)-".$this->db->escape($data['holiday']).")*100,1) as 'Percent_Present'
        
        FROM mss_emss_attendance,mss_employees
               
        where 
               
                mss_emss_attendance.working_hours >= 0
                AND mss_employees.employee_id=mss_emss_attendance.employee_id
                AND mss_emss_attendance.employee_outlet_id=1
                AND mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."
               
        group BY 
                mss_emss_attendance.employee_id,month(mss_emss_attendance.attendance_date)";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function DataAttendanceShow($data)
    {
        if($data['group_name'] == 'days_present'){
            return $this->GetPresentAttendance($data);
        }
        elseif ($data['group_name'] == 'days_absent') {
            return $this->GetAbsentAttendance($data);
        } 
       elseif ($data['group_name'] == 'half_days') {
            return $this->GetHalfDayAttendance($data);
        } 
        elseif ($data['group_name'] == 'percentage_attendance') {
            return $this->GetPercentAttendance($data); 
        } 
        elseif ($data['group_name'] == 'attendance') {
            return $this->GetAttendanceAll($data); 
        } 
   
        else{
            $this->ModelHelper(false,true,"No such report exists!");
        }  
    }
// Salary Calculation For Month
    public function EmployeeSalaryCalculation($data)
    {
     
        $sql="SELECT * FROM mss_emss_commision WHERE emp_id=".$this->db->escape($data['expert_id'])." AND months=".$this->db->escape($data['month'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    
    // Salary Calculation For All Employees
    public function EmployeeSalaryCalculationAll($data)
    {
      
        $sql="SELECT * FROM mss_emss_commision_all WHERE bussiness_outlet_id=".$this->db->escape($data['business_outlet_id'])." AND month=".$this->db->escape($data['month'])."";

        $query = $this->db->query($sql);
  
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        } 
    }

    public function EmployeeGetSalary($data)
    {
        
        $sql="SELECT employee_gross_salary,employee_basic_salary,employee_pf,employee_gratuity FROM mss_employees WHERE employee_id=".$this->db->escape($data['expert_id'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }

    public function EmployeeOverTimeCalculate($data)
    {
        $sql="SELECT mss_employees.employee_over_time_rate,sum(mss_emss_attendance.over_time) as 'over_time' FROM mss_emss_attendance,mss_employees WHERE mss_employees.employee_id=".$this->db->escape($data['expert_id'])."
        AND mss_emss_attendance.employee_id=mss_employees.employee_id
        AND Month(mss_emss_attendance.attendance_date)=month(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
         AND Year(mss_emss_attendance.attendance_date)=Year(CONCAT((".$this->db->escape($data['month'])."),'-','00'))";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }    
    }

    public function CheckSalaryDefined($data)
    {
        $sql="SELECT * FROM mss_emss_salary WHERE expert_id=".$this->db->escape($data['expert_id'])." AND month=".$this->db->escape($data['month'])."";

        $query = $this->db->query($sql);
        
        if($query->num_rows()>0){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    
    public function CheckAdvanceSalaryDefined($data){ 
        
        $sql = "SELECT * FROM mss_emss_advance_salary WHERE month(month)=month(CONCAT((".$this->db->escape($data['month'])."),'-','00')) AND employee_id=".$this->db->escape($data['expert_id'])."";  
        $query = $this->db->query($sql);
        
        $query = $this->db->query($sql);
        // print_r($query);
        // exit;
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function ShowSalary($data)
    {
        $sql="SELECT * FROM mss_emss_salary WHERE expert_id=".$this->db->escape($data['expert_id'])." AND month=".$this->db->escape($data['month'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    public function EmpDetails()
	{
        $sql="SELECT * FROM mss_employees WHERE mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']." AND mss_employees.employee_is_active=1";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    

// Get Salary Till Date 

  public function GetSalaryTillDate($data){
        $sql="SELECT employee_id,employee_first_name,employee_last_name,employee_gross_salary,employee_basic_salary,employee_pf,employee_gratuity,employee_others,(mss_employees.employee_gross_salary)/30 as Salary,employee_pt,employee_income_tax from mss_employees
        where employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."
        AND employee_business_admin=".$this->session->userdata['logged_in']['business_admin_id']."
        AND employee_is_active=1";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    // Advance table data fetch
   public function AdvanceSalaryDetails($data){
        $sql="SELECT * FROM mss_emss_advance_salary WHERE  month(month) =month(CURRENT_DATE) AND business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']."
        ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
//Get Commision Details
   public function CommissionDetails($data){
       
        $sql="SELECT * from mss_emss_commision where Month(CONCAT(months,'-00'))=Month(CURRENT_DATE) AND Year(CONCAT(months,'-00'))=Year(CURRENT_DATE) AND mss_emss_commision.emp_id=".$this->db->escape($data)." AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']."
        ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }


    // bBase value for commission
    public function CommissionBaseValueForSalary($data){
        // $this->PrintArray($data);
        // exit;
        if($data['commission_type'] == 'all'){
            if($data['base_value']=='TotalSales' || $data['base_value']=='Total Sales MRP' )
            {
            //total sales
                $service = $this->SalaryCommissionTotalSalesB($data);
                $package = $this->PackagessalesCommissionMrp($data);
                $total=$service['res_arr'][0]['total']+$package['res_arr'][0]['package_sales'];
                return $total;
                // $this->PrettyPrintArray($result);
            }
            elseif($data['base_value']=='Total Sales Net Price' || $data['base_value']=='ServiceSales' || $data['base_value']=='Total Sales Discount')
            {
                //total on discounted price
                $service = $this->SalaryCommissionServiceValue($data);
                $package = $this->PackagessalesCommission($data);
                $total=$service['res_arr'][0]['total']+$package['res_arr'][0]['package_sales'];
                // $this->PrettyPrintArray($total);
                return $total;
            }
        }elseif($data['commission_type'] == 'service'){
            //total sales or net sales
            if($data['base_value']=='TotalSales' || $data['base_value']=='Total Sales MRP' )
            {
            //total sales
                $result=$this->ServiceSalesMrp($data);
                $result=$result['res_arr'][0]['total'];
                return $result; 
            }
            elseif($data['base_value']=='Total Sales Net Price' || $data['base_value']=='ServiceSales' || $data['base_value']=='Total Sales Discount')
            {
                //total on discounted price
                $result=$this->ServiceSalesNetPrice($data);
                $result=$result['res_arr'][0]['total'];
                return $result;
            }
        }elseif($data['commission_type'] == 'product'){
            if($data['base_value']=='TotalSales' || $data['base_value']=='Total Sales MRP' )
            {
            //total sales
                $result=$this->ProductSalesMrp($data);
                $result=$result['res_arr'][0]['total'];
                return $result; 
            }
            elseif($data['base_value']=='Total Sales Net Price' || $data['base_value']=='ServiceSales' || $data['base_value']=='Total Sales Discount')
            {
                //total on discounted price
                $result=$this->ProductSalesNetPrice($data);
                $result=$result['res_arr'][0]['total'];
                return $result;
            }
        }elseif($data['commission_type'] == 'package'){
            if($data['base_value']=='TotalSales' || $data['base_value']=='Total Sales MRP' )
            {
            //total sales
                $package = $this->PackagessalesCommissionMrp($data);
                $total=$package['res_arr'][0]['package_sales'];
                return $total;
                // $this->PrettyPrintArray($result);
            }
            elseif($data['base_value']=='Total Sales Net Price' || $data['base_value']=='ServiceSales' || $data['base_value']=='Total Sales Discount')
            {
                //total on discounted price
                $package = $this->PackagessalesCommission($data);
                $total=$package['res_arr'][0]['package_sales'];
                return $total;
            }
        }    
    }
      //   Total Sales amt for salary
      public function SalaryCommissionTotalSalesB($commission){
     $sql="SELECT 
     Round(sum(((mss_services.service_price_inr)*(mss_services.service_gst_percentage)/100)+ mss_services.service_price_inr)) as 'total' FROM mss_transaction_services,mss_transactions,mss_services,mss_employees,mss_business_outlets
     WHERE
     mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
     AND
     mss_transaction_services.txn_service_service_id = mss_services.service_id
     AND
     mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
     AND
     mss_employees.employee_id =".$this->db->escape($commission['emp_id'])."
     AND
     mss_employees.employee_business_outlet = mss_business_outlets.business_outlet_id
     AND
     mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
    AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1";
      $query = $this->db->query($sql);
      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      }
  }
     //service sales for salary
     public function SalaryCommissionServiceValue($commission){
         $sql="SELECT 
         SUM(mss_transaction_services.txn_service_discounted_price) AS 'total'
         FROM
         mss_transactions,
         mss_transaction_services,
         mss_employees,
         mss_customers,
         mss_categories,
         mss_sub_categories,
         mss_services
         WHERE
         mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
         AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
         AND mss_employees.employee_id=".$this->db->escape($commission['emp_id'])."
         AND mss_transaction_services.txn_service_service_id = mss_services.service_id
         AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
         AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
         AND mss_transactions.txn_customer_id = mss_customers.customer_id
         AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-
         1 DAY) AND CURRENT_DATE-1";
         $query = $this->db->query($sql);
         if($query){
         return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
         return $this->ModelHelper(false,true,"DB error!");   
         } 
     }


  //Get Commision Details
  public function GetSalaryDetails($data){
    $sql="SELECT mss_employees.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,mss_emss_salary_details.salary,mss_emss_salary_details.advance,mss_emss_salary_details.commission,mss_emss_salary_details.payout
    from mss_employees,mss_emss_salary_details
    where mss_emss_salary_details.employee_id=".$this->db->escape($data['expert_id'])."
    AND (mss_emss_salary_details.Date)=month(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
    AND mss_emss_salary_details.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
    ";

    $query = $this->db->query($sql);
    
    if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
    }
    else{
        return $this->ModelHelper(false,true,"DB error!");   
    }   
}

// Get Attendance For Loading Default Page
    public function GetAttendanceDetails($data){
    $sql="SELECT
    mss_emss_attendance.employee_id,
    mss_employees.employee_first_name,
    mss_employees.employee_last_name,
    (DATEDIFF(CURRENT_DATE-1,DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY))+1) as 'Calender_Days',
    ((DATEDIFF(CURRENT_DATE-1,DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY))+1))-".$this->db->escape($data['holiday'])." as 'Working-Days',
    count(mss_emss_attendance.attendance_id) as 'Present',
    (sum(mss_emss_attendance.over_time)/60)*mss_employees.employee_over_time_rate as 'overtime',
    round((sum(mss_emss_attendance.over_time)/60),1) 'AttenOverTime',
    (((DATEDIFF(CURRENT_DATE-1,DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY))+1))-".$this->db->escape($data['holiday']).")-count(mss_emss_attendance.attendance_id) as 'Absent',
    ".$this->db->escape($data['holiday'])." as 'Holidays', 
    round((count(mss_emss_attendance.attendance_id))/((DATEDIFF(CURRENT_DATE-1,DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY))+1)-".$this->db->escape($data['holiday']).")*100,1) as 'Percent_Present'
    FROM mss_emss_attendance,mss_employees
    where 
           mss_emss_attendance.working_hours >= 0
            AND mss_employees.employee_id=mss_emss_attendance.employee_id
            AND mss_employees.employee_is_active=1
            AND mss_emss_attendance.employee_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND mss_emss_attendance.attendance_date BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
           
    group BY 
            mss_emss_attendance.employee_id";

    $query = $this->db->query($sql);
    
    if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
    }
    else{
        return $this->ModelHelper(false,true,"DB error!");   
    }   
}

// Get Attendance For KPI of Attendance mgmt system -> Attendance
public function GetAttendanceAll($data){
    // $this->PrintArray($data);
    // exit;
    $sql="SELECT
    mss_emss_attendance.employee_id,
    mss_employees.employee_first_name,
    mss_employees.employee_last_name,
    DATE_FORMAT(mss_emss_attendance.attendance_date,'%b-%Y') as 'month',
    DAY(LAST_DAY(CONCAT((".$this->db->escape($data['month'])."),'-','00'))) as 'Calender_Days',
    ((DAY(LAST_DAY(CONCAT((".$this->db->escape($data['month'])."),'-','00')))))-".$this->db->escape($data['holiday'])." as 'Working_Days',
    round((sum(mss_emss_attendance.over_time)/60),1) as 'AttenOverTime',
    count(mss_emss_attendance.attendance_id) as 'Present',
    ((DAY(LAST_DAY(CONCAT((".$this->db->escape($data['month'])."),'-','00'))))-".$this->db->escape($data['holiday']).")-count(mss_emss_attendance.attendance_id) as 'Leave',
    ".$this->db->escape($data['holiday'])." as 'Holidays', 
    round((count(mss_emss_attendance.attendance_id))/(DAY(LAST_DAY(CONCAT((".$this->db->escape($data['month'])."),'-','00')))-".$this->db->escape($data['holiday']).")*100,1) as 'Percent_Present'
    FROM mss_emss_attendance,mss_employees
    where 
           mss_emss_attendance.working_hours >= 0
            AND mss_employees.employee_id=mss_emss_attendance.employee_id
            AND mss_emss_attendance.employee_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND mss_employees.employee_is_active=1
            AND month(mss_emss_attendance.attendance_date) = Month(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
            AND Year(mss_emss_attendance.attendance_date)=Year(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
           
    group BY 
            mss_emss_attendance.employee_id";
    $query = $this->db->query($sql);
    
    if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
    }
    else{
        return $this->ModelHelper(false,true,"DB error!");   
    }   
}

    // Holiday count for Load view of attendance
     public function EmssGetHolidaysLoad($data){

        $sql="SELECT count(holiday_id) as 'holiday'
        FROM mss_emss_holidays 
        WHERE 
        holiday_date  BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))- 1 DAY) AND CURRENT_DATE-1
        AND holiday_business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']."";
        $query = $this->db->query($sql);
        // print_r($query);
        // exit;
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }    // Calculayte Half Day for Load Attendance
     public function GetHalfDayAttendanceLoad($data)
    {
        $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(mss_emss_attendance.attendance_id) as 'Half_Day' FROM mss_emss_attendance,mss_employees
        where mss_emss_attendance.working_hours < ((mss_employees.working_hours*10)*0.5)
        AND mss_employees.employee_is_active=1
        AND mss_employees.employee_id=mss_emss_attendance.employee_id
        AND mss_emss_attendance.employee_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
         AND mss_emss_attendance.attendance_date BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-
        1 DAY) AND CURRENT_DATE-1
        group BY mss_emss_attendance.employee_id";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

   //
   // Calculayte Half Day for report and attendance kpi
    public function GetHalfDayAttendanceKpi($data)
   {
       
    //     $this->PrintArray($data);
    //    exit;
       $sql="SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,mss_employees.employee_last_name,count(mss_emss_attendance.attendance_id) as 'Half_Day' FROM mss_emss_attendance,mss_employees
       where 
        mss_emss_attendance.employee_id=mss_employees.employee_id
        AND mss_emss_attendance.working_hours <= (mss_employees.working_hours*0.5)
        AND mss_employees.employee_is_active=1
        AND mss_emss_attendance.employee_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND month(mss_emss_attendance.attendance_date) = month(CONCAT((".$this->db->escape($data['month'])."),'-','01'))
        AND Year(mss_emss_attendance.attendance_date)=Year(CONCAT((".$this->db->escape($data['month'])."),'-','01'))
       AND  mss_employees.employee_id=".$this->db->escape($data['emp_id'])."";


       $query = $this->db->query($sql);
       
       if($query){
           return $this->ModelHelper(true,false,'',$query->result_array());
       }
       else{
           return $this->ModelHelper(false,true,"DB error!");   
       }
   } 
 


    // Get All Salary Details
    public function GetAllSalaryDetails($data)
    {
        $sql="SELECT * FROM mss_employees,mss_emss_salary_details where mss_employees.employee_id=mss_emss_salary_details.employee_id AND mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."";
 
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    } 
    // Get All Salary Details
    public function GetAdvanceSalaryDetails($data)
    {
        $sql="SELECT * FROM mss_employees,mss_emss_advance_salary where mss_employees.employee_id=mss_emss_advance_salary.employee_id AND mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."";
 
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	} 
	//jitesh
	 //Loyalty Rule Offers
     public function GetOutletLoyalty($data)
     {
         $this->db->select("rule_type");
         $this->db->from("mss_loyalty_rules");
         $this->db->where("business_outlet_id",$data);
         $this->db->where("rule_status",1);
         $query= $this->db->get();
         if($query->num_rows() > 0)
         {
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else
         {
             return $this->ModelHelper(false,true,"No Rule Found");
         }
     }
     public function InsertOffer($data,$table_name)
     {
         if($this->db->insert($table_name,$data)){
             $data = array('insert_id' => $this->db->insert_id());
             return $this->ModelHelper(true,false,'',$data);
         }
         else{
             return $this->ModelHelper(false,true,"Check your inserted query!");
         }
     }
     public function InsertVisitOffer($data,$table_name)
     {
         if($this->db->insert($table_name,$data)){
             $data = array('insert_id' => $this->db->insert_id());
             return $this->ModelHelper(true,false,'',$data);
         }
         else{
             return $this->ModelHelper(false,true,"Check your inserted query!");
         }
     }
     public function UpdateVisitOffer($data,$table_name,$where)
     {
         $this->db->where($where, $data[$where]);
         $this->db->update($table_name, $data);
         if($this->db->affected_rows() > 0){
             return $this->ModelHelper(true,false);    
         }
         else{
             return $this->ModelHelper(false,true,"No row updated!");
         }    
         
     }
     public function UpdateOffer($data,$table_name,$where)
     {
         $this->db->where($where, $data[$where]);
         $this->db->update($table_name, $data);
         if($this->db->affected_rows() > 0){
             return $this->ModelHelper(true,false);    
         }
         else{
             return $this->ModelHelper(false,true,"No row updated!");
         }    
     }
     //Existing Loyalty Offer
     public function ExistingLoyaltyOffer($data,$table_name){ 
         $this->db->select('*');
         $this->db->from($table_name);
         $this->db->where('business_outlet_id',$data);
         $this->db->order_by('visits','points');
         // $sql = "SELECT * FROM ".$table_name." WHERE business_outlet_id = ".$data." ORDER BY visits,points";
         $query = $this->db->get();
         // print_r($query);
         // exit;
         if($query->num_rows()>0){
             return $this->ModelHelper(true,false,'',$query->result_array());
             die;
         }
         else{
             return $this->ModelHelper(false,true,'No offer Defined',$query->result_array());
             die;   
         } 
     }
     public function CheckExistingLoyaltyOffer($data,$table_name){ 
         // $this->db->select('*');
         // $this->db->from('mss_loyalty_offer');
         // $this->db->where('business_outlet_id',$data,'business_admin_id',$data,'points',$data,'offers',$data);
         
         $sql = "SELECT * FROM ".$table_name."
                 WHERE 
                     business_outlet_id =" .$this->db->escape($data['business_outlet_id']).
                 "AND
                     business_admin_id =".$this->db->escape($data['business_admin_id']).
                 "AND
                     offers = ".$this->db->escape($data['offers']).
                 "AND 
                     points =" .$this->db->escape($data['points']).";";  
         $query = $this->db->query($sql);
         
         if($query->num_rows() > 0){
             return $this->ModelHelper(true,false,'',$query->result_array());
             die;
         }
         else{
             return $this->ModelHelper(false,true,'',$query->result_array());
             die;   
         } 
     }
     public function DeleteOfferIntegrated($data,$table_name,$where)
     {
        $this->db->where($where, $data[$where]);
        $this->db->update($table_name, $data);
        if($this->db->affected_rows() > 0){
            return $this->ModelHelper(true,false);    
        }
        else{
            return $this->ModelHelper(false,true,"Error in Deleting offer");
        }    
     }
     public function RuleDetailsById($id,$table_name,$where)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where,$id);
        $this->db->where('rule_status',1);
    
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
           return $this->ModelHelper(true,false,'',$query->row_array());
        } 
        else{
           return $this->ModelHelper(false,true,"Duplicate rows found!");
        }
    }
     
      //calculate weekoff
	 public function CalWeekOff($data)
    {
        $sql="SELECT employee_id,employee_weekoff FROM mss_employees where mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_employees.employee_is_active=1";
 
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	} 
	public function CalWeekOffMonth($data){
        $sql="SELECT employee_id,employee_weekoff FROM mss_employees where mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_employees.employee_is_active=1";
    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
	public function CalWeekOffMonthSal($data){
        $sql="SELECT employee_weekoff FROM mss_employees where mss_employees.employee_id=".$this->db->escape($data['expert_id'])."
        AND mss_employees.employee_is_active=1
        AND
         mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."";
    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }

	public function CheckCommissionDefined($data){ 
      
        $sql = "SELECT * FROM mss_emss_commision WHERE months=".$this->db->escape($data['month'])." AND emp_id=".$this->db->escape($data['expert_id'])."";  
        $query = $this->db->query($sql);
        
        $query = $this->db->query($sql);
        // print_r($query);
        // exit;
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
       public function CommissionBaseValueForSalaryMonth($data)
  {
        // $this->PrintArray($data);
	    // exit;
        if($data['base_value']=='Total Sales MRP' || $data['base_value']=='TotalSales')
        {
        return $this->CommissionTotalSalesMonth($data); 
        }
        elseif($data=='Total Sales Net Price' || $data['base_value']=='ServiceSales' || $data['base_value']=='Total Sales Discount')
        {
            return $this->CommissionServiceSalesMonth($data);
        }
    
  }

  //   Total Sales amt for commisiion
  public function CommissionTotalSalesMonth($data){
       $sql="SELECT 
       Round(sum(((mss_services.service_price_inr)*(mss_services.service_gst_percentage)/100)+ mss_services.service_price_inr)) as 'total' FROM mss_transaction_services,mss_transactions,mss_services,mss_employees,mss_business_outlets
       WHERE
       mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
       AND
       mss_transaction_services.txn_service_service_id = mss_services.service_id
       AND
       mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
       AND
       mss_employees.employee_id =".$this->db->escape($data['expert_id'])."
       AND
       mss_employees.employee_business_outlet = mss_business_outlets.business_outlet_id
       AND
       mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
       AND
       month(mss_transactions.txn_datetime) = month(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
       AND
       Year(mss_transactions.txn_datetime) = Year(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
        ";
      $query = $this->db->query($sql);
      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      }
  }
  //
  public function CommissionServiceSalesMonth($data)
  {
      $sql="SELECT                      
      ROUND(SUM(mss_transaction_services.txn_service_discounted_price)) AS 'total'
      FROM
              mss_transactions,
              mss_transaction_services,
              mss_employees,
              mss_customers,
              mss_categories,
              mss_sub_categories,
              mss_services
      WHERE
              mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
              AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
              AND mss_transaction_services.txn_service_service_id = mss_services.service_id
              AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
              AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
              AND mss_transactions.txn_customer_id = mss_customers.customer_id
              AND mss_employees.employee_id=".$this->db->escape($data['expert_id'])."
              AND mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."
              AND month(date(mss_transactions.txn_datetime)) = month(CONCAT((".$this->db->escape($data['month'])."),'-','00'))
              AND Year(date(mss_transactions.txn_datetime))=Year(CONCAT((".$this->db->escape($data['month'])."),'-','00'))";
      $query = $this->db->query($sql);
      

      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      } 
  }
    public function GetOverTimeDetails($data){
        $sql="SELECT mss_employees.employee_id,(sum(mss_emss_attendance.over_time)/60)*mss_employees.employee_over_time_rate as 'overtime' FROM mss_employees,mss_emss_attendance WHERE mss_employees.employee_id=mss_emss_attendance.employee_id AND mss_emss_attendance.employee_id=".$this->db->escape($data['expert_id'])." AND mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']." AND mss_employees.employee_is_active=1 
        AND month(mss_emss_attendance.attendance_date) = Month(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
        AND Year(mss_emss_attendance.attendance_date) = Year(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))";
    
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    public function EmssGetHolidaysMonth($data)
    {

        $sql="SELECT count(holiday_id) as 'holiday'
        FROM mss_emss_holidays 
        WHERE 
        month(holiday_date) = Month(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
        AND Year(holiday_date) = Year(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
        AND holiday_business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']."";
        $query = $this->db->query($sql);
        // print_r($query);
        // exit;
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
    public function CalAbsentMonthSal($data){
    // print_r($data);
    // exit;
    $sql="SELECT
   
    (DAY(LAST_DAY(CONCAT((".$this->db->escape($data['month_name'])."),'-','00')))-".$this->db->escape($data['holiday']).")-count(mss_emss_attendance.attendance_id)-".$this->db->escape($data['weekoff'])." as 'Leave'
   
    FROM mss_emss_attendance,mss_employees
    where 
           mss_emss_attendance.working_hours >= 0
            AND mss_emss_attendance.employee_id=".$this->db->escape($data['expert_id'])."
            AND mss_emss_attendance.employee_id=mss_employees.employee_id
            AND mss_emss_attendance.employee_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND month(mss_emss_attendance.attendance_date) = Month(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
     		AND Year(mss_emss_attendance.attendance_date) = Year(CONCAT((".$this->db->escape($data['month_name'])."),'-','00'))
           ";

    $query = $this->db->query($sql);
    
    if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
    }
    else{
        return $this->ModelHelper(false,true,"DB error!");   
    }   
}

    public function GetEmployeesServiceSales(){
        $sql="SELECT                      
                ROUND(SUM(mss_transactions.txn_value)/3) AS 'service_sales'
                FROM
                        mss_transactions,
                        mss_transaction_services,
                        mss_employees,
                        mss_customers,
                        mss_categories,
                        mss_sub_categories,
                        mss_services
                WHERE
                        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                        AND mss_transactions.txn_customer_id = mss_customers.customer_id
                        AND mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."
                        AND date(mss_transactions.txn_datetime) BETWEEN (last_day(CURRENT_DATE) + interval 1 day - interval 4 month) AND LAST_DAY(now() - INTERVAL 1 MONTH)
            GROUP BY mss_employees.employee_id ";
                    
          $query = $this->db->query($sql);
          

          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
      }
   public function GetBusinessAdminEmployees(){
        $sql="SELECT * FROM
                        mss_employees
                WHERE
                        mss_employees.employee_business_outlet=".$this->session->userdata['outlets']['current_outlet']."
                        AND mss_employees.employee_is_active=1
                        ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    } 
    //   public function SelectMaxIdVendor(){
    //     $sql="SELECT MAX(vendor_id) as 'id' FROM mss_vendors";
                    
    //       $query = $this->db->query($sql);
          
    //       if($query){
    //       return $this->ModelHelper(true,false,'',$query->result_array());
    //       }
    //       else{
    //       return $this->ModelHelper(false,true,"DB error!");   
    //       } 
    //   }
    //   public function GetVendorDetails(){
    //     $sql="SELECT * FROM
    //                     mss_vendors where business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
    //                     ";
                    
    //       $query = $this->db->query($sql);
          
    //       if($query){
    //       return $this->ModelHelper(true,false,'',$query->result_array());
    //       }
    //       else{
    //       return $this->ModelHelper(false,true,"DB error!");   
    //       } 
    //   }
      
//       public function GetPendingPayment(){
//     $sql="SELECT * FROM mss_expenses WHERE expense_status='Unpaid'";
                 
//       $query = $this->db->query($sql);
      
//       if($query){
//       return $this->ModelHelper(true,false,'',$query->result_array());
//       }
//       else{
//       return $this->ModelHelper(false,true,"DB error!");   
//       } 
//   }
  
     public function GetVendorDetails($data){
    $sql="SELECT * FROM
                    mss_vendors where   business_outlet_id=".$this->db->escape($data['outlet_id'])."
                    ";
                
      $query = $this->db->query($sql);
      
      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      } 
  }

  public function SelectMaxIdVendor(){
    $sql="SELECT count(business_outlet_id) as 'id' FROM mss_vendors where business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
                
      $query = $this->db->query($sql);
      

      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      } 
  } 

   public function SelectMaxIdExpense($data){
    $sql="SELECT count(bussiness_outlet_id) as 'id' FROM mss_expenses where bussiness_outlet_id=".$this->db->escape($data['outlet_id'])."";
                
      $query = $this->db->query($sql);
      
      if($query){
      return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
      return $this->ModelHelper(false,true,"DB error!");   
      } 
  }


 public function GetPendingPayment(){
        // $sql="SELECT * FROM mss_expenses,mss_expense_types WHERE expense_status='Unpaid' AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
                    
        $sql="SELECT * FROM mss_expense_types,mss_expenses_unpaid WHERE mss_expense_types.expense_type_id = mss_expenses_unpaid.expense_type_id AND mss_expense_types.expense_type_business_admin_id =".$this->session->userdata['logged_in']['business_admin_id']."  AND mss_expense_types.expense_type_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." AND (expense_status='Unpaid' OR expense_status='Partialy_paid')";
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    } 

    public function UpdateExpense($data){
        // $sql="SELECT * FROM mss_expenses,mss_expense_types WHERE expense_status='Unpaid' AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
                     
        $sql="UPDATE mss_expenses_unpaid SET amount=".$this->db->escape($data['amount']).",pending_amount=".$this->db->escape($data['pending_amount']).",payment_mode=".$this->db->escape($data['payment_mode']).",expense_status=".$this->db->escape($data['expense_status'])." WHERE expense_id=".$this->db->escape($data['expense_id'])."";
          $query = $this->db->query($sql);  
          
          if($query){
          return $this->ModelHelper(true,false);
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function GetBusinessAdminEmployeesC($data){
        $sql="SELECT * FROM
                        mss_employees
                WHERE
                        mss_employees.employee_business_outlet=".$this->db->escape($data['outlet_id'])."
                        AND mss_employees.employee_is_active=1
                        ";
                    
        $query = $this->db->query($sql);
        
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    
    public function GetBalancePaidBack($data){
        $sql="SELECT sum(mss_transaction_settlements.txn_settlement_balance_paid) as 'balance_paid' FROM mss_transaction_settlements,mss_transactions,mss_customers
        WHERE 
            date(mss_transactions.txn_datetime)=date(now())
        AND mss_transaction_settlements.txn_settlement_txn_id=mss_transactions.txn_id    
        AND mss_transactions.txn_customer_id=mss_customers.customer_id
        AND mss_transactions.txn_status=1
        AND mss_customers.customer_business_admin_id=".$this->db->escape($data['business_admin_id'])."
        AND mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."";
            $query = $this->db->query($sql);
            
            if($query->num_rows()>0){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    

  
    //26/03/2020
    //top 5 services of outlet
    public function GetTopServices(){
        $sql = "SELECT 
        date(mss_transactions.txn_datetime) AS 'Billing Date', 
        mss_categories.category_name AS 'Category', 
        mss_transaction_services.txn_service_service_id as 'service_id',
        mss_services.service_name 'service_name',
        mss_services.service_price_inr,
        mss_services.service_est_time,
        mss_services.service_gst_percentage,
        mss_sub_categories.sub_category_name AS 'Sub-Category',
        COUNT(mss_transaction_services.txn_service_service_id) AS 'Total Service',
        SUM(mss_transaction_services.txn_service_discounted_price) AS 'Total Amount' 
        FROM 
        mss_transactions, 
        mss_transaction_services, 
        mss_categories, 
        mss_sub_categories,
        mss_services
        WHERE 
        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
        AND mss_transaction_services.txn_service_service_id = mss_services.service_id   
        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_categories.category_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
        AND mss_categories.category_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
        AND month(mss_transactions.txn_datetime) = month(CURRENT_DATE)-1 
        GROUP BY 
        mss_services.service_id 
        ORDER BY 
        COUNT(mss_services.service_id) DESC
        LIMIT 5";
        //execute the query
        $query = $this->db->query($sql);
        if ($query->num_rows() >0){
        return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
        return $this->ModelHelper(false,true,"No Data Found!");
        } 
    }
    public function ServiceDetailsByOutlet(){
        // $sql="SELECT * FROM mss_expenses,mss_expense_types WHERE expense_status='Unpaid' AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
           // print_r($this->session->userdata['outlets']['current_outlet']);     
           $sql="SELECT
           mss_services.*,
           mss_categories.category_name,
           mss_categories.category_type,
           mss_sub_categories.sub_category_name
           from
           mss_services,mss_categories,mss_sub_categories
           WHERE
           mss_services.service_sub_category_id=mss_sub_categories.sub_category_id AND
           mss_sub_categories.sub_category_category_id=mss_categories.category_id AND
           mss_categories.category_business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']."";
           $query = $this->db->query($sql);
           
           if($query){
           return $this->ModelHelper(true,false,'',$query->result_array());
           }
           else{
           return $this->ModelHelper(false,true,"DB error!");   
           } 
    }
    public function ExpenseReports($where){
        // $sql="SELECT * FROM mss_expenses,mss_expense_types WHERE expense_status='Unpaid' AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
        // print_r($this->session->userdata['outlets']['current_outlet']);     
        $sql="SELECT mss_expenses.expense_unique_serial_id as 'Expense Id',
        mss_expenses.item_name as 'Expense Name',
        mss_expense_types.expense_type as 'Expense_Category',
        mss_expenses.expense_date as 'Date',
        mss_expenses.employee_name as 'Cahier Name',
        mss_expenses.total_amount as 'Total Amount',
        mss_expenses.amount as 'Amount Paid',
        mss_expenses.pending_amount as 'Pending Amount',
        mss_expenses.payment_type as 'Payment Type',
        mss_expenses.payment_to_name as 'Payemnt To',
        mss_expenses.employee_name as 'Paid By',
        mss_expenses.payment_mode as 'Payment Mode',
        mss_expenses.expense_status as 'Payment Status',
        mss_expenses.remarks as 'Remark',
        mss_expenses.invoice_number as 'Invoice No'
        FROM mss_expense_types,mss_expenses 
        WHERE 
            mss_expense_types.expense_type_id = mss_expenses.expense_type_id 
        AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['business_admin_id'])." 
        AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
            AND mss_expenses.expense_status != 'Unpaid' 
        Order by mss_expenses.expense_date desc";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }  
    public function GetCompleteCustomer($customer_id){
        // $this->PrintArray($customer_id);
        // exit;
        $this->db->select('*');
        $this->db->from('mss_customers');
        $this->db->where('customer_id',$customer_id);
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() == 1){
            $result = array();
            $result = $query->row_array();

            $sql = "SELECT  mss_transactions.txn_value,mss_transactions.txn_discount,date(mss_transactions.txn_datetime) AS BillDate FROM mss_customers,mss_transactions WHERE mss_customers.customer_id = mss_transactions.txn_customer_id AND mss_customers.customer_id = ".$this->db->escape($customer_id)." ORDER BY mss_transactions.txn_datetime DESC LIMIT 4";            
            
                  $query = $this->db->query($sql);
            if($query){
                
                $result['transactions'] = $query->result_array();
                // return $this->ModelHelper(true,false,'',$result);
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            }
            //
            $sql = "SELECT 
                        SUM(mss_transactions.txn_value)'total',
                        COUNT(mss_transactions.txn_id)'total_visit',
                        CAST(AVG(mss_transactions.txn_value) AS DECIMAL(10,2))'avg_value',
                        MAX(date(mss_transactions.txn_datetime))'last_visit'
                    FROM
                        mss_transactions
                    WHERE
                        mss_transactions.txn_customer_id=".$this->db->escape($customer_id)." ";            
            
                  $query = $this->db->query($sql);
            if($query){
                
                $result['customer_transaction'] = $query->result_array();
                return $this->ModelHelper(true,false,'',$result);
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            }
        } 
        elseif($query->num_rows === 0){
           return $this->ModelHelper(false,true,"Customer not found!");
        }
        else{
            return $this->ModelHelper(false,true,"Duplicate row found!");   
        }
    }
    //01-04-2020
    public function GetVendorDetail($data){
        // $this->PrintArray($data);
        // exit;
        $sql="SELECT * FROM mss_vendors where vendor_id=".$this->db->escape($data)."";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    public function ExpenseReport7Days($where){
        // $sql="SELECT * FROM mss_expenses,mss_expense_types WHERE expense_status='Unpaid' AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
                // print_r($this->session->userdata['outlets']['current_outlet']);     
                $sql="SELECT mss_expenses.expense_unique_serial_id as 'Expense Id',
                mss_expenses.item_name as 'Expense Name',
                mss_expense_types.expense_type as 'Expense_Category',
                mss_expenses.expense_date as 'Date',
                mss_expenses.employee_name as 'Cahier Name',
                mss_expenses.total_amount as 'Total Amount',
                mss_expenses.amount as 'Amount Paid',
                mss_expenses.pending_amount as 'Pending Amount',
                mss_expenses.payment_type as 'Payment Type',
                mss_expenses.payment_to_name as 'Payemnt To',
                mss_expenses.employee_name as 'Paid By',
                mss_expenses.payment_mode as 'Payment Mode',
                mss_expenses.expense_status as 'Payment Status',
                mss_expenses.remarks as 'Remark',
                mss_expenses.invoice_number as 'Invoice No'
                FROM mss_expense_types,mss_expenses 
                WHERE 
                    mss_expense_types.expense_type_id = mss_expenses.expense_type_id 
                AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['business_admin_id'])." 
                AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
                AND mss_expenses.expense_status != 'Unpaid' 
                AND mss_expenses.expense_date BETWEEN CURRENT_DATE-INTERVAL 7 DAY AND CURRENT_DATE-INTERVAL 1 DAY
                Order by mss_expenses.expense_date desc";
                $query = $this->db->query($sql);
                if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
                }
                else{
                return $this->ModelHelper(false,true,"DB error!");   
                } 
    }   
    public function ExpenseReport30Days($where){
        // $sql="SELECT * FROM mss_expenses,mss_expense_types WHERE expense_status='Unpaid' AND bussiness_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
                // print_r($this->session->userdata['outlets']['current_outlet']);     
                $sql="SELECT mss_expenses.expense_unique_serial_id as 'Expense Id',
                mss_expenses.item_name as 'Expense Name',
                mss_expense_types.expense_type as 'Expense_Category',
                mss_expenses.expense_date as 'Date',
                mss_expenses.employee_name as 'Cahier Name',
                mss_expenses.total_amount as 'Total Amount',
                mss_expenses.amount as 'Amount Paid',
                mss_expenses.pending_amount as 'Pending Amount',
                mss_expenses.payment_type as 'Payment Type',
                mss_expenses.payment_to_name as 'Payemnt To',
                mss_expenses.employee_name as 'Paid By',
                mss_expenses.payment_mode as 'Payment Mode',
                mss_expenses.expense_status as 'Payment Status',
                mss_expenses.remarks as 'Remark',
                mss_expenses.invoice_number as 'Invoice No'
                FROM mss_expense_types,mss_expenses 
                WHERE 
                    mss_expense_types.expense_type_id = mss_expenses.expense_type_id 
                AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['business_admin_id'])." 
                AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
                AND mss_expenses.expense_status != 'Unpaid' 
                AND mss_expenses.expense_date  BETWEEN CURRENT_DATE-INTERVAL 30 DAY AND CURRENT_DATE-INTERVAL 1 DAY
                Order by mss_expenses.expense_date desc";
                $query = $this->db->query($sql);
                if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
                }
                else{
                return $this->ModelHelper(false,true,"DB error!");   
                } 
    }    
    public function ExpenseReportMTD($where){
                $sql="SELECT mss_expenses.expense_unique_serial_id as 'Expense Id',
                mss_expenses.item_name as 'Expense Name',
                mss_expense_types.expense_type as 'Expense_Category',
                mss_expenses.expense_date as 'Date',
                mss_expenses.employee_name as 'Cahier Name',
                mss_expenses.total_amount as 'Total Amount',
                mss_expenses.amount as 'Amount Paid',
                mss_expenses.pending_amount as 'Pending Amount',
                mss_expenses.payment_type as 'Payment Type',
                mss_expenses.payment_to_name as 'Payemnt To',
                mss_expenses.employee_name as 'Paid By',
                mss_expenses.payment_mode as 'Payment Mode',
                mss_expenses.expense_status as 'Payment Status',
                mss_expenses.remarks as 'Remark',
                mss_expenses.invoice_number as 'Invoice No'
                FROM mss_expense_types,mss_expenses 
                WHERE 
                    mss_expense_types.expense_type_id = mss_expenses.expense_type_id 
                AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['business_admin_id'])." 
                AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
                AND mss_expenses.expense_status != 'Unpaid' 
                AND mss_expenses.expense_date BETWEEN  DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-
                1 DAY) AND CURRENT_DATE-INTERVAL 1 MONTH
                Order by mss_expenses.expense_date desc";
                $query = $this->db->query($sql);
                if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
                }
                else{
                return $this->ModelHelper(false,true,"DB error!");   
                } 
    }  
    public function ExpenseReportRange($where){
        $sql="SELECT mss_expenses.expense_unique_serial_id as 'Expense Id',
        mss_expenses.item_name as 'Expense Name',
        mss_expense_types.expense_type as 'Expense_Category',
        mss_expenses.expense_date as 'Date',
        mss_expenses.employee_name as 'Cahier Name',
        mss_expenses.total_amount as 'Total Amount',
        mss_expenses.amount as 'Amount Paid',
        mss_expenses.pending_amount as 'Pending Amount',
        mss_expenses.payment_type as 'Payment Type',
        mss_expenses.payment_to_name as 'Payemnt To',
        mss_expenses.employee_name as 'Paid By',
        mss_expenses.payment_mode as 'Payment Mode',
        mss_expenses.expense_status as 'Payment Status',
        mss_expenses.remarks as 'Remark',
        mss_expenses.invoice_number as 'Invoice No'
        FROM mss_expense_types,mss_expenses 
        WHERE 
            mss_expense_types.expense_type_id = mss_expenses.expense_type_id 
        AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['business_admin_id'])." 
        AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
        AND mss_expenses.expense_status != 'Unpaid' 
        AND mss_expenses.expense_date BETWEEN  ".$this->db->escape($where['from_date'])."  AND ".$this->db->escape($where['to_date'])." 
        Order by mss_expenses.expense_date desc";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    public function ServiceVisitSales($where){
            $sql = "select count(DISTINCT mss_transactions.txn_customer_id) as 'visit',
            SUM(mss_transactions.txn_value) as 'service' 
            from mss_transactions,mss_customers 
            where 
            date(mss_transactions.txn_datetime) = CURRENT_DATE 
            AND mss_transactions.txn_customer_id = mss_customers.customer_id
            AND mss_customers.customer_business_admin_id=".$this->db->escape($where['business_admin_id'])."
            AND mss_customers.customer_business_outlet_id=".$this->db->escape($where['business_outlet_id'])."
            ";
            //execute the query
            $query = $this->db->query($sql);
            if ($query->num_rows() >0){
               return $this->ModelHelper(true,false,'',$query->result_array());
            } 
            else{
               return $this->ModelHelper(false,true,"No Data Found!");
            } 
        }
    public function PackageVisitSales($where){
            $sql = "SELECT count(DISTINCT mss_package_transactions.package_txn_customer_id) as 'visit',
            SUM(mss_package_transactions.package_txn_value)  as 'packages'
            from mss_package_transactions,mss_customers
            where date(mss_package_transactions.datetime) = date(now())
            AND mss_package_transactions.package_txn_customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_admin_id=".$this->db->escape($where['business_admin_id'])."
            AND mss_customers.customer_business_outlet_id=".$this->db->escape($where['business_outlet_id'])."
            ";
            //execute the query
            $query = $this->db->query($sql);
            if ($query->num_rows() >0){
               return $this->ModelHelper(true,false,'',$query->result_array());
            } 
            else{
               return $this->ModelHelper(false,true,"No Data Found!");
            } 
        }
    public function GetAllAppointmentsCount($where){
            $sql = "SELECT
                        COUNT(mss_appointments.appointment_id) as 'count'
                    FROM
                        mss_appointments,
                        mss_appointment_services,
                        mss_categories,
                        mss_sub_categories,
                        mss_services,
                        mss_employees,
                        mss_customers
                    WHERE
                        mss_appointments.customer_id = mss_customers.customer_id
                        AND mss_services.service_sub_category_id=mss_sub_categories.sub_category_id
                        AND mss_sub_categories.sub_category_category_id=mss_categories.category_id
                        AND mss_appointments.appointment_id = mss_appointment_services.appointment_id
                        AND mss_appointments.appointment_date = CURRENT_DATE
                        AND mss_appointments.appointment_start_time >= CURRENT_TIME
                        AND mss_appointment_services.expert_id = mss_employees.employee_id
                        AND mss_appointment_services.service_id = mss_services.service_id
                        AND mss_customers.customer_business_admin_id = ".$this->db->escape($where['business_admin_id'])."
                        AND mss_customers.customer_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])."
                        AND mss_employees.employee_business_outlet = ".$this->db->escape($where['business_outlet_id'])."
                        AND mss_employees.employee_business_admin = ".$this->db->escape($where['business_admin_id'])."
                        AND mss_appointments.appointment_status = 1 ";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }   
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            }   
        }
        public function TxnHistory($data){
        $sql = "SELECT * FROM mss_transactions_replica WHERE master_admin_id=".$this->session->userdata['logged_in']['master_admin_id']."    ORDER BY txn_datetime DESC LIMIT 10";
        //execute the query
        $query = $this->db->query($sql);
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
    }
        public function TxnHistoryPackages($where){
            $sql = "SELECT * FROM mss_package_transactions_replica where master_admin_id=".$this->session->userdata['logged_in']['master_admin_id']."  ORDER BY datetime DESC LIMIT 10";
            //execute the query
            $query = $this->db->query($sql);
            if ($query->num_rows() >0){
               return $this->ModelHelper(true,false,'',$query->result_array());
            } 
            else{
               return $this->ModelHelper(false,true,"No Data Found!");
            } 
        }
        public function TxnHistoryServices($where){
            $sql = "select cust_name,cust_id,cust_mobile,service_name,sum(txn_service_quantity) as 'count',max(txn_datetime) as 'last_visit',business_outlet_name from mss_transaction_services_replica where master_admin_id=".$this->session->userdata['logged_in']['master_admin_id']." AND service_type='service'
            group by service_name,cust_id  ORDER BY count DESC LIMIT 10
            ";
            //execute the query
            $query = $this->db->query($sql);
            if ($query->num_rows() >0){
               return $this->ModelHelper(true,false,'',$query->result_array());
            } 
            else{
               return $this->ModelHelper(false,true,"No Data Found!");
            } 
        }
        public function TxnHistoryProduct($where){
            $sql = "select cust_name,cust_id,cust_mobile,service_name,sum(txn_service_quantity) as 'count',max(txn_datetime) as 'last_visit',business_outlet_name from mss_transaction_services_replica where master_admin_id=".$this->session->userdata['logged_in']['master_admin_id']." AND service_type='otc'
            group by service_name,cust_id ORDER BY count DESC LIMIT 10
            ";
            //execute the query
            $query = $this->db->query($sql);
            if ($query->num_rows() >0){
               return $this->ModelHelper(true,false,'',$query->result_array());
            } 
            else{
               return $this->ModelHelper(false,true,"No Data Found!");
            } 
        }
        //11-04-2020
        public function MenuManagementServices($where){
            $sql = "SELECT * FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND A.category_business_admin_id = ".$this->db->escape($where['category_business_admin_id'])." AND C.service_is_active = ".$this->db->escape($where['service_is_active'])." AND A.category_business_outlet_id = ".$this->db->escape($where['category_business_outlet_id'])." AND C.service_type = ".$this->db->escape($where['service_type'])." order by C.service_id desc";
             $query = $this->db->query($sql);
             if($query){
                 return $this->ModelHelper(true,false,'',$query->result_array());
             }
             else{
                 return $this->ModelHelper(false,true,"DB error!");   
             }
         }
         public function SearchProductDetails($search_term,$inventory_type,$business_admin_id,$business_outlet_id){
            $sql = "SELECT 
                       *
                    FROM 
                        mss_barcode
                    WHERE
                        (mss_barcode.item_name LIKE '$search_term%' OR  mss_barcode.barcode LIKE '$search_term%')
                        ";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            }
        }
        //14-04
    public function OTCReports($where){
        // $this->PrintArray($data);
        // exit;
        $sql="SELECT C.service_name as 'Service Name',
        A.category_name as 'Category',
        B.sub_category_name as 'Sub-Category',
        C.inventory_type as 'Inventory Type',
        CONCAT(C.qty_per_item,' ',C.service_unit) as 'SKU SIZE',
        C.barcode as 'Barcode',
        C.service_brand as 'Brand',
        C.service_price_inr as 'Gross Price',
        C.service_price_inr*C.service_gst_percentage/100 as 'GST',
        C.service_price_inr+C.service_price_inr*C.service_gst_percentage/100 as 'MRP'
        FROM 
        mss_categories AS A,
        mss_sub_categories AS B,
        mss_services AS C 
        WHERE 
        A.category_id = B.sub_category_category_id 
        AND B.sub_category_id = C.service_sub_category_id 
        AND A.category_business_admin_id = ".$this->db->escape($where['business_admin_id'])."  
        AND C.service_is_active = 1
        AND A.category_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
        AND C.service_type = ".$this->db->escape($where['type'])." 
        order by C.service_id desc";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    public function ServiceReports($where){
        // $this->PrintArray($data);
        // exit;
        $sql="SELECT C.service_name as 'Service Name',
        A.category_name as 'Category',
        B.sub_category_name as 'Sub-Category',
        C.service_price_inr as 'Gross Price',
        C.service_price_inr*C.service_gst_percentage/100 as 'GST',
        C.service_price_inr+C.service_price_inr*C.service_gst_percentage/100 as 'MRP'
        FROM 
        mss_categories AS A,
        mss_sub_categories AS B,
        mss_services AS C 
        WHERE 
        A.category_id = B.sub_category_category_id 
        AND B.sub_category_id = C.service_sub_category_id 
        AND A.category_business_admin_id = ".$this->db->escape($where['business_admin_id'])."  
        AND C.service_is_active = 1
        AND A.category_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." 
        AND C.service_type = ".$this->db->escape($where['type'])." 
        order by C.service_id desc";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    //15-04
    private function GetStockReportInventory($data){
        $sql = "SELECT 
        mss_business_outlets.business_outlet_name as 'Outlet Name',
        mss_inventory.barcode_id as 'Barcode Id',
        mss_inventory.barcode as 'Barcode',
        mss_services.service_name as 'Product Name',
        mss_sub_categories.sub_category_name as 'Sub Category',
        mss_categories.category_name as 'Category',
        mss_inventory.brand_name as 'Brand Name',
        mss_inventory.usg_category as 'Usg Type',
        mss_inventory.sku_size as 'SKU Size',
        mss_inventory.unit as 'Unit',
        mss_inventory.sku_count as 'Total Stock'
        FROM mss_inventory,mss_business_outlets,mss_sub_categories,mss_categories,mss_services
        where 
        mss_inventory.service_id=mss_services.service_id
        AND mss_inventory.outlet_id = mss_business_outlets.business_outlet_id
        AND mss_services.service_sub_category_id=mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id=mss_categories.category_id
        AND mss_inventory.business_admin_id=".$this->db->escape($data['business_admin_id'])." 
        AND mss_inventory.outlet_id=".$this->db->escape($data['business_outlet_id'])." ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    private function TransactionInventoryReport($data){
        $sql = "SELECT
        mss_inventory_transaction.txn_id as 'Txn ID',
        mss_inventory_transaction.datetime as 'Date',
        mss_inventory_transaction.invoice_no as 'Invoice No',
        mss_inventory_transaction.source_type as 'Source Type',
        mss_inventory_transaction.source_name as 'Source Name',
        mss_inventory_transaction.invoice_amt as 'Invoice',
        mss_inventory_transaction.barcode as 'Barcode',
        mss_inventory_transaction.sku_count as 'SKU Count',
        mss_inventory_transaction.usg_category as 'Usg CAtegory',
        mss_inventory_transaction.brand_name as 'Brand Name',
        mss_inventory_transaction.product_type as 'Product Type',
        mss_inventory_transaction.sku_size as 'SKU SIze',
        mss_inventory_transaction.gst as 'GST',
        mss_inventory_transaction.mrp as 'MRP'
        FROM mss_inventory_transaction
        WHERE 
        mss_inventory_transaction.business_admin_id=".$this->db->escape($data['business_admin_id'])." 
        AND mss_inventory_transaction.outlet_id=".$this->db->escape($data['business_outlet_id'])."
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
     //Get Due Customer
    public function GetDueCustomer($where){
        $sql = "SELECT 
        mss_customers.customer_name,
        mss_customers.customer_mobile,
        mss_customers.customer_pending_amount,
        mss_business_outlets.business_outlet_name,
        mss_business_outlets.business_outlet_location,
        mss_business_outlets.business_outlet_sender_id,
        mss_business_outlets.api_key
    FROM
        mss_customers,
        mss_business_outlets
    WHERE
        mss_customers.customer_business_outlet_id=".$this->db->escape($where['business_outlet_id'])." AND
        mss_customers.customer_business_admin_id=".$this->db->escape($where['business_admin_id'])." AND
        mss_business_outlets.business_outlet_id=".$this->db->escape($where['business_outlet_id'])." AND
        mss_business_outlets.business_outlet_business_admin=".$this->db->escape($where['business_admin_id'])." AND
        mss_customers.customer_pending_amount >=100 ";
        //execute the query
        $query = $this->db->query($sql);
        
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
    }
    //23-04-2020
    //counter for commission insert 
    public function Countercommission(){
        $sql="SELECT max(counter) as 'max'  from mss_emss_commision                    
        ";
         $query = $this->db->query($sql);
         if($query){
         return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
         return $this->ModelHelper(false,true,"DB error!");   
         }
     }
     //packages sales net value for commision 21-04
    private function PackagessalesCommission($data){
        $sql = "SELECT 
        SUM(mss_package_transactions.package_txn_value) AS package_sales
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND Month(mss_package_transactions.datetime) = Month(now())
        AND Year(mss_package_transactions.datetime) = Year(now())
        AND mss_package_transactions.package_txn_expert=".$this->db->escape($data['emp_id'])."
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // package sale mro fro comision
    private function PackagessalesCommissionMrp($data){
        $sql = "SELECT 
        SUM(mss_package_transactions.package_txn_value+mss_package_transactions.package_txn_discount) AS package_sales
        FROM
        mss_package_transactions,
        mss_package_transaction_settlements,
        mss_customers
        WHERE
        mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
        mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND Month(mss_package_transactions.datetime) = Month(now())
        AND Year(mss_package_transactions.datetime) = Year(now())
        AND mss_package_transactions.package_txn_expert=".$this->db->escape($data['emp_id'])."
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // service sales total sales mrp
    private function ServiceSalesMrp($data){
        $sql = "SELECT                      
        ROUND(SUM(mss_transactions.txn_value)) AS 'total'
        FROM
                mss_transactions,
                mss_transaction_services,
                mss_employees,
                mss_customers,
                mss_categories,
                mss_sub_categories,
                mss_services
        WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                AND mss_services.service_type='service'
                AND mss_transactions.txn_status=1
                AND mss_transactions.txn_customer_id = mss_customers.customer_id
                AND mss_employees.employee_id=".$this->db->escape($data['emp_id'])."
                AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // service sales total sales net price
    private function ServiceSalesNetPrice($data){
        $sql = "SELECT 
        SUM(mss_transaction_services.txn_service_discounted_price) AS 'total'
        FROM
        mss_transactions,
        mss_transaction_services,
        mss_employees,
        mss_customers,
        mss_categories,
        mss_sub_categories,
        mss_services
        WHERE
        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
        AND mss_employees.employee_id=".$this->db->escape($data['emp_id'])."
        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_services.service_type='service'
        AND mss_transactions.txn_status=1
        AND mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-
        1 DAY) AND CURRENT_DATE-1
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
     // Product sales total sales mrp
     private function ProductSalesMrp($data){
        $sql = "SELECT                      
        ROUND(SUM(mss_transactions.txn_value)) AS 'total'
        FROM
                mss_transactions,
                mss_transaction_services,
                mss_employees,
                mss_customers,
                mss_categories,
                mss_sub_categories,
                mss_services
        WHERE
                mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
                AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                AND mss_services.service_type='otc'
                AND mss_transactions.txn_status=1
                AND mss_transactions.txn_customer_id = mss_customers.customer_id
                AND mss_employees.employee_id=".$this->db->escape($data['emp_id'])."
                AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // Product sales total sales net price
    private function ProductSalesNetPrice($data){
        $sql = "SELECT 
        SUM(mss_transaction_services.txn_service_discounted_price) AS 'total'
        FROM
        mss_transactions,
        mss_transaction_services,
        mss_employees,
        mss_customers,
        mss_categories,
        mss_sub_categories,
        mss_services
        WHERE
        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
        AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
        AND mss_employees.employee_id=".$this->db->escape($data['emp_id'])."
        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_services.service_type='otc'
        AND mss_transactions.txn_status=1
        AND mss_transactions.txn_customer_id = mss_customers.customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-
        1 DAY) AND CURRENT_DATE-1
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
     //27-04-2020
    // engagement Customer
    // 25-04
    public function NewCustomer($data){
        $sql="SELECT mss_customers.customer_name as 'Customer Name' , 
					mss_customers.customer_mobile as 'Mobile', 
					Count(mss_transactions.txn_id)  as 'Visits',
					FORMAT(SUM(mss_transactions.txn_value),0) as 'LTV', 
					FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
					date(MAX(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
					mss_customers.last_visit_branch AS 'Last Visited Store',
					FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
					FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
					FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'New' As 'Category'	     
			
			FROM mss_customers , 
				mss_transactions,
				mss_business_outlets
				WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
			AND  mss_customers.customer_business_outlet_id= mss_business_outlets.business_outlet_id
			AND mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." 
			AND mss_transactions.txn_status =1 
			group by mss_customers.customer_id
			HAVING COUNT(mss_transactions.txn_id) =1 ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function RepeatingCustomerService($data){
        $sql="SELECT mss_customers.customer_name as 'Customer Name' , 
        mss_customers.customer_mobile as 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) as 'LTV', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        date(MAX(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
	    mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
	    FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'New' As 'Category'  
		FROM mss_customers , mss_transactions,mss_business_outlets
			WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
		AND  mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
		AND mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." 
		
		AND mss_transactions.txn_status =1 
		group by mss_customers.customer_id
		HAVING COUNT(mss_transactions.txn_id) BETWEEN ".$this->db->escape($data['r1'])." and ".$this->db->escape($data['r2'])."  ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function RegularCustomer($data){
        $sql="SELECT mss_customers.customer_name as 'Customer Name' , 
        mss_customers.customer_mobile as 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) as 'LTV', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        date(MAX(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
	    mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
	    FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'New' As 'Category'	     
  
		FROM mss_customers , mss_transactions,
		mss_business_outlets
			WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
		AND  mss_customers.customer_business_outlet_id= mss_business_outlets.business_outlet_id
		AND mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." 
		
		AND mss_transactions.txn_status =1 
		group by mss_customers.customer_id
		HAVING COUNT(mss_transactions.txn_id) > ".$this->db->escape($data['regular_cust'])." ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function RiskCustomerService($data){
        $sql = "SELECT  mss_customers.customer_id,
		mss_customers.customer_name as 'Customer Name' , 
        mss_customers.customer_mobile as 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) as 'LTV', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        max(date(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
		mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
		FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'LOST' As 'Category'  
		FROM mss_customers , mss_transactions, mss_business_outlets
		WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
		AND  mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
		AND mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
		AND mss_transactions.txn_status =1 
		group by mss_customers.customer_id
		HAVING MAX(date(mss_transactions.txn_datetime)) BETWEEN (CURRENT_DATE - INTERVAL ".$this->db->escape($data['at_risk_cust2'])." day) and (CURRENT_DATE- INTERVAL ".$this->db->escape($data['at_risk_cust'])." day) ";
	// $sql = str_replace(",)",")",$sql);
        $query = $this->db->query($sql);
		// if($query){
        //     $result = $query->result_array();
        //     $customer_id = $result[0]['customer_id'];
        //     if(!empty($customer_id)){
        //          $sql = "SELECT mss_customers.customer_id FROM mss_customers WHERE mss_customers.customer_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." AND mss_customers.customer_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']." AND mss_customers.customer_id NOT IN ($customer_id)";
        //             // $sql = str_replace(",)",")",$sql);
        //             $query = $this->db->query($sql);
        //     }
           
        // }
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	
    public function RiskCustomerPackage($data){
        $sql="SELECT mss_customers.customer_id
        FROM
        mss_customers
        WHERE
        mss_customers.customer_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_customers.customer_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
        AND
        mss_customers.customer_id NOT IN
        (SELECT mss_package_transactions.package_txn_id
         FROM
            mss_package_transactions,
             mss_customers,
             mss_business_outlets,
             mss_business_admin
         WHERE
             mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
         AND
             mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
         AND
             mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
         AND
             mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
         AND
             mss_business_admin.business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
         AND
             DATE(mss_package_transactions.datetime) > CURRENT_DATE - INTERVAL ".$this->db->escape($data['at_risk_cust'])." DAY
        )  
            ";
$sql = str_replace(",)",")",$sql);
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
     public function LostCustomerService($data){
            $sql = "SELECT 
			mss_customers.customer_name as 'Name',
			mss_customers.customer_mobile as 'Mobile',
			'Regular' as 'Category',
			SUM(mss_transactions.txn_value) as 'Total_Spend',
			Max(mss_transactions.txn_datetime) as 'Last_Visit_Date' 
			from 
			mss_transactions,
			mss_customers 
			WHERE 
			mss_transactions.txn_customer_id = mss_customers.customer_id
			AND date(mss_transactions.txn_datetime) < (CURRENT_DATE - INTERVAL  ".$this->db->escape($data['lost_customer'])." DAY)
			AND mss_customers.customer_business_outlet_id= ".$this->session->userdata['outlets']['current_outlet']."
			GROUP BY mss_transactions.txn_customer_id";
        //  $sql = str_replace(",)",")",$sql);
        $query = $this->db->query($sql);
        #echo $sql;die;
        if($query){
            $result = $query->result_array();
            $customer_id = $result[0]['customer_id'];
		if(!empty($customer_id)){
                $sql = "SELECT mss_customers.customer_id 
                    FROM   mss_customers 
                    WHERE  mss_customers.customer_business_outlet_id = 1 
                           AND mss_customers.customer_business_admin_id = 1 
                           AND mss_customers.customer_id NOT IN ($customer_id) ";
                            //    $sql = str_replace(",)",")",$sql);
                                $query = $this->db->query($sql);
            }   
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }else{
                return $this->ModelHelper(false,true,"DB error!");       
            }
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function LostCustomerPackage($data){
        $sql="SELECT mss_customers.customer_id
        FROM
        mss_customers
        WHERE
        mss_customers.customer_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_customers.customer_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
        AND
        mss_customers.customer_id NOT IN
        (SELECT mss_package_transactions.package_txn_id
         FROM
            mss_package_transactions,
             mss_customers,
             mss_business_outlets,
             mss_business_admin
         WHERE
             mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
         AND
             mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
         AND
             mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
         AND
             mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
         AND
             mss_business_admin.business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
         AND
             DATE(mss_package_transactions.datetime) > CURRENT_DATE - INTERVAL ".$this->db->escape($data['lost_customer'])." DAY
        )  
            ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    //28-04-2020
    // Reports Customer Timeline
    public function ReportAllCustomer($data){
        $sql="SELECT mss_customers.customer_name as 'Name',
		mss_customers.customer_mobile as 'Mobile',
		'ALL' as 'Category'
		FROM mss_customers 
        WHERE  mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']." 
        Group By mss_customers.customer_id
       ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ReportNewCustomer($data){
        $sql="SELECT mss_customers.customer_name as 'Name',
		mss_customers.customer_mobile as 'Mobile',
		'New' as 'Category',
		sum(mss_transactions.txn_value) as 'Total_Spend',
		Max(mss_transactions.txn_datetime) as 'Last_Visit_Date'
		FROM mss_transactions,
			mss_customers 
		WHERE 
			mss_transactions.txn_customer_id = mss_customers.customer_id
			AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
				GROUP BY mss_transactions.txn_customer_id
				HAVING count(mss_transactions.txn_customer_id)=1 ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ReportRepeatCustomer($data){
        $sql="SELECT 
        mss_customers.customer_name as 'Name',mss_customers.customer_mobile as 'Mobile','Repeat' as 'Category',SUM(mss_transactions.txn_value) as 'Total_Spend',Max(mss_transactions.txn_datetime) as 'Last_Visit_Date' 
        from 
            mss_transactions,
			mss_customers 
        WHERE 
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions.txn_customer_id
        HAVING 
        count(mss_transactions.txn_customer_id) >= ".$this->db->escape($data['r1'])." AND COUNT(mss_transactions.txn_customer_id) <= ".$this->db->escape($data['r2'])."
        ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ReportRegularCustomer($data){
        $sql="SELECT 
            mss_customers.customer_name as 'Name',mss_customers.customer_mobile as 'Mobile','Regular' as 'Category',SUM(mss_transactions_replica.txn_value) as 'Total_Spend',Max(mss_transactions_replica.txn_datetime) as 'Last_Visit_Date' 
            from 
            mss_transactions_replica,mss_customers 
            WHERE 
            mss_transactions_replica.txn_customer_id = mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            GROUP BY mss_transactions_replica.txn_customer_id
            HAVING 
            count(mss_transactions_replica.txn_customer_id) > ".$this->db->escape($data['regular_cust'])."
        ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	
	public function NoRiskCustomer($data){
        $sql="SELECT mss_customers.customer_name as 'Customer Name' , 
        mss_customers.customer_mobile as 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) as 'LTV', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        date(MAX(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
		mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
		FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'NO RISK' As 'Category'	     
  
			FROM mss_customers , mss_transactions,
			mss_business_outlets
			WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
			AND mss_customers.customer_business_outlet_id= mss_business_outlets.business_outlet_id
			AND mss_business_outlets.business_outlet_id= ".$this->session->userdata['outlets']['current_outlet']."
			AND mss_transactions.txn_status =1 
			group by mss_customers.customer_id
			HAVING MAX(date(mss_transactions.txn_datetime)) > CURRENT_DATE - INTERVAL ".$this->db->escape($data['no_risk'])." DAY ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}

	public function ReportNoRiskCustomer($data){
        $sql="SELECT mss_customers.customer_name as 'Name' , 
        mss_customers.customer_mobile as 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) as 'Total_Spend', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        date(MAX(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
		mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
		FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'NO RISK' As 'Category'	     
  
			FROM mss_customers , mss_transactions,
			mss_business_outlets
			WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
			AND mss_customers.customer_business_outlet_id= mss_business_outlets.business_outlet_id
			AND mss_business_outlets.business_outlet_id= ".$this->session->userdata['outlets']['current_outlet']."
			AND mss_transactions.txn_status =1 
			group by mss_customers.customer_id
			HAVING MAX(date(mss_transactions.txn_datetime)) > CURRENT_DATE - INTERVAL ".$this->db->escape($data['no_risk'])." DAY ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	
	public function DormantCustomer($data){
        $sql="SELECT  mss_customers.customer_id,
		mss_customers.customer_name AS 'Name', 
        mss_customers.customer_mobile AS 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) AS 'Total_Spend', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        max(date(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
		mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
		FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'LOST' As 'Category'  
		FROM mss_customers , mss_transactions, mss_business_outlets
		WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
		AND  mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
		AND mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
		AND mss_transactions.txn_status =1 
		group by mss_customers.customer_id
		HAVING MAX(date(mss_transactions.txn_datetime)) BETWEEN (CURRENT_DATE - INTERVAL ".$this->db->escape($data['dormant_r2'])." day) and (CURRENT_DATE- INTERVAL ".$this->db->escape($data['dormant_r1'])." day) ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}

	

	public function ReportDormantCustomer($data){
        $sql="SELECT  mss_customers.customer_id,
		mss_customers.customer_name AS 'Name' , 
        mss_customers.customer_mobile AS 'Mobile', 
        Count(mss_transactions.txn_id)  as 'Visits',
        FORMAT(SUM(mss_transactions.txn_value),0) as 'Total_Spend', 
        FORMAT(AVG(mss_transactions.txn_value),0) as 'Avg Order Value', 
        max(date(mss_transactions.txn_datetime)) as 'Last_Visit_Date',
		mss_customers.last_visit_branch AS 'Last Visited Store',
        FORMAT(mss_customers.customer_rewards,2) as 'Rewards',
		FORMAT(mss_customers.customer_virtual_wallet,0) as 'Virtual Wallet Amt',
        FORMAT(mss_customers.customer_pending_amount,0) as 'Amount Due', 'LOST' As 'Category'  
		FROM mss_customers , mss_transactions, mss_business_outlets
		WHERE  mss_customers.customer_id=mss_transactions.txn_customer_id
		AND  mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
		AND mss_business_outlets.business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
		AND mss_transactions.txn_status =1 
		group by mss_customers.customer_id
		HAVING MAX(date(mss_transactions.txn_datetime)) BETWEEN (CURRENT_DATE - INTERVAL ".$this->db->escape($data['dormant_r2'])." day) and (CURRENT_DATE- INTERVAL ".$this->db->escape($data['dormant_r1'])." day) ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ReportRiskSCustomer($data){
        // $this->PrettyPrintArray($data);
        $sql="SELECT 
        mss_customers.customer_id,mss_customers.customer_name as 'Name',mss_customers.customer_mobile as 'Mobile','Risk' as 'Category',SUM(mss_transactions_replica.txn_value) as 'Total_Spend',Max(mss_transactions_replica.txn_datetime) as 'Last_Visit_Date' 
        from 
        mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id = mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_customers.customer_id=".$this->db->escape($data)."";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ReportRiskPCustomer($data){
        // $this->PrettyPrintArray($data);
        $sql="SELECT mss_customers.customer_name as 'Name',mss_customers.customer_mobile as 'Mobile','Risk' as 'Category',SUM(mss_package_transactions.package_txn_value) as 'Total_Spend',Max(mss_package_transactions.datetime) as 'Last_Visit_Date'  
        from 
                mss_package_transactions,mss_customers 
        WHERE 
            mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_customers.customer_id=".$this->db->escape($data)."";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ReportLostSCustomer($data){
        // $this->PrettyPrintArray($data);
        $sql="SELECT 
        mss_customers.customer_id,mss_customers.customer_name as 'Name',mss_customers.customer_mobile as 'Mobile','Lost' as 'Category',SUM(mss_transactions_replica.txn_value) as 'Total_Spend',Max(mss_transactions_replica.txn_datetime) as 'Last_Visit_Date' 
        from 
        mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id = mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_customers.customer_id=".$this->db->escape($data)."";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function SearchCustomerTimeline($data){
        // $this->PrettyPrintArray($data);
        $sql="SELECT mss_customers.customer_name as 'Name',
		mss_customers.customer_mobile as 'Mobile',
		count(mss_transactions.txn_id) as 'Category',
		sum(mss_transactions.txn_value) as 'Total_Spend',
		Max(date(mss_transactions.txn_datetime)) as 'Last_Visit_Date'
        FROM mss_transactions,mss_customers 
        WHERE mss_transactions.txn_customer_id = mss_customers.customer_id
        AND mss_customers.customer_id=$data ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    //29-04-2020
     //01-05-2020
    public function SelectCounterForTags(){
            $sql="SELECT MAX(tag_id) as 'counter' FROM mss_tags_rule";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function GetTags(){
      $sql="SELECT * from mss_tags_rule Where business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." group by tag_id order by id desc";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    public function GetDetailsByTagId($data){
      $sql="SELECT * from mss_tags_rule Where tag_id = $data ";
        $query = $this->db->query($sql);
        if($query){
        return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        } 
    }
    public function DownloadTagRules($data){
        if($data['kpi'] == 'total_amt_spent'){
                return $this->DRuleTotalAmountSpent($data);
        }   elseif ($data['kpi'] == 'visits') {
                return $this->DRuleVisits($data);
        }   elseif ($data['kpi'] == 'last_visit_date') {
                return $this->DRuleLastVisitDate($data);
        }   elseif ($data['kpi'] == 'last_visit_range') {
                return $this->DRuleLastVisitRange($data);
        }   elseif ($data['kpi'] == 'points') {
                return $this->DRulePoints($data); 
        }   elseif ($data['kpi'] == 'gender') {
                return $this->DRuleGender($data); 
        }   elseif ($data['kpi'] == 'age') {
                return $this->DRuleAge($data); 
        }   elseif ($data['kpi'] == 'package_subscriber') {
                return $this->DRulePackageSubscriber($data); 
        }   elseif ($data['kpi'] == 'avg_order') {
                return $this->DRuleAverageOrder($data); 
        }   elseif ($data['kpi'] == 'billing_date') {
                return $this->DRuleBillingDate($data); 
        }   elseif ($data['kpi'] == 'birthday') {
                return $this->DRuleBirthday($data); 
        }   elseif ($data['kpi'] == 'anniversary') {
                return $this->DRuleAnniversary($data); 
        }   elseif ($data['kpi'] == 'package_expiry') {
                return $this->DRulePackageExpiry($data); 
        }   else{
            $this->ModelHelper(false,true,"No such report exists!");
        }  
    }
    public function RuleTotalAmountSpent($data){
        $sql="Select 
        txn_customer_id as 'cust_id' from mss_transactions_replica,mss_customers 
                WHERE 
                    mss_transactions_replica.txn_customer_id=mss_customers.customer_id
                    AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
                GROUP BY mss_transactions_replica.txn_customer_id
                HAVING sum(mss_transactions_replica.txn_value) BETWEEN ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."
           ";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function RuleVisits($data){
            $sql="Select mss_transactions_replica.txn_customer_id as 'cust_id' from mss_transactions_replica,mss_customers 
                    WHERE 
                        mss_transactions_replica.txn_customer_id=mss_customers.customer_id
                        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
                    GROUP BY mss_transactions_replica.txn_customer_id
                    HAVING count(mss_transactions_replica.txn_id) BETWEEN ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."
               ";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function RuleLastVisitDate($data){
        $sql="Select mss_transactions_replica.txn_customer_id as 'cust_id' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id  
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions_replica.txn_customer_id
        HAVING MAX(date(mss_transactions_replica.txn_datetime)) = ".$this->db->escape($data['date'])."
        ";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function RulePoints($data){
        $sql="select mss_customers.customer_id as 'cust_id' from mss_customers
        Where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND (mss_customers.customer_rewards) BETWEEN  ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function RuleGender($data){
        $sql="Select mss_customers.customer_id as 'cust_id' from mss_customers
        Where 
            mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND mss_customers.customer_title = ".$this->db->escape($data['select_option'])."";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function RuleAge($data){
        $sql="Select mss_customers.customer_id as 'cust_id',mss_customers.customer_dob from mss_customers
        where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
        TIMESTAMPDIFF(YEAR,mss_customers.customer_dob,CURRENT_DATE) BETWEEN ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function RuleAverageOrder($data){
        $sql="Select mss_transactions_replica.txn_customer_id as 'cust_id' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions_replica.txn_customer_id
        HAVING sum(mss_transactions_replica.txn_value)/count(mss_transactions_replica.txn_id) =  ".$this->db->escape($data['value'])."
        ";
                $query = $this->db->query($sql);
                if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
                }
                else{
                return $this->ModelHelper(false,true,"DB error!");   
                } 
    }
    public function RuleBillingDate($data){
        $sql="Select mss_transactions_replica.txn_customer_id as 'cust_id' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND date(mss_transactions_replica.txn_datetime) BETWEEN ".$this->db->escape($data['start_range_date'])." AND ".$this->db->escape($data['end_range_date'])."
        GROUP BY mss_transactions_replica.txn_customer_id
     ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function RuleBirthday($data){
        $sql="Select mss_customers.customer_id as 'cust_id' from mss_customers
        where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ANd mss_customers.customer_dob BETWEEN ".$this->db->escape($data['start_range_date'])." AND ".$this->db->escape($data['end_range_date'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function RuleAnniversary($data){
        $sql="Select mss_customers.customer_id as 'cust_id' from mss_customers
        where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ANd mss_customers.customer_doa BETWEEN ".$this->db->escape($data['start_range_date'])." AND ".$this->db->escape($data['end_range_date'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function RulePackageSubscriber($data){
        $sql="Select mss_customer_packages.customer_id as 'cust_id' from mss_customer_packages,mss_customers
        where 
            mss_customer_packages.customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function RulePackageExpiry($data){
        $sql="Select mss_customer_packages.customer_id as 'cust_id' from mss_customer_packages,mss_customers
        where 
            mss_customer_packages.customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND DATEDIFF(mss_customer_packages.package_expiry_date,CURRENT_DATE) = ".$this->db->escape($data['value'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DeleteTagRules($data){
        $sql="Delete from mss_tags_rule where tag_id=".$this->db->escape($data['tag_id'])."";
          $query = $this->db->query($sql);
        //   $this->PrettyPrintArray($query);
          if($query){
          return $this->ModelHelper(true,false,'Deleted Successful');
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");
          } 
    }
    public function EditTagRules($data){
        $sql="Select * from mss_tags_rule where tag_id=".$this->db->escape($data['tag_id'])."";
          $query = $this->db->query($sql);
        //   $this->PrettyPrintArray($query);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");
          } 
    }
    public function DRulePackageSubscriber($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,mss_customer_packages.package_expiry_date,'Package Subscriber' from mss_customer_packages,mss_customers
        where 
            mss_customer_packages.customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRulePackageExpiry($data){
        $sql="Select    mss_customers.customer_name,mss_customers.customer_mobile,mss_customer_packages.package_expiry_date,'Package Expiry' from mss_customer_packages,mss_customers
        where 
            mss_customer_packages.customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND DATEDIFF(mss_customer_packages.package_expiry_date,CURRENT_DATE) = ".$this->db->escape($data['value'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleTotalAmountSpent($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,sum(mss_transactions_replica.txn_value),'Total Amount Spent' from mss_transactions_replica,mss_customers 
                WHERE 
                    mss_transactions_replica.txn_customer_id=mss_customers.customer_id
                    AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
                GROUP BY mss_transactions_replica.txn_customer_id
                HAVING sum(mss_transactions_replica.txn_value) BETWEEN ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."
           ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleVisits($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,count(mss_transactions_replica.txn_id),'Visits' from mss_transactions_replica,mss_customers 
                WHERE 
                    mss_transactions_replica.txn_customer_id=mss_customers.customer_id
                    AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
                GROUP BY mss_transactions_replica.txn_customer_id
                HAVING count(mss_transactions_replica.txn_id) BETWEEN ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."
            ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleLastVisitDate($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,MAX(date(mss_transactions.txn_datetime)),'Last Visit Date' from mss_transactions,mss_customers 
        WHERE 
            mss_transactions.txn_customer_id=mss_customers.customer_id  
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions.txn_customer_id
        HAVING MAX(date(mss_transactions.txn_datetime)) = ".$this->db->escape($data['date'])."
        UNION
         SELECT mss_customers.customer_name,mss_customers.customer_mobile,MAx(date(mss_package_transactions.datetime)),'Last Visit Date' from mss_package_transactions,mss_customers
        WHERE 
        mss_package_transactions.package_txn_customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_package_transactions.package_txn_customer_id
        HAVING MAx(date(mss_package_transactions.datetime)) = ".$this->db->escape($data['date'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRulePoints($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,mss_customers.customer_rewards,'Points' from mss_customers 
        Where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND (mss_customers.customer_rewards) BETWEEN  ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleGender($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,mss_customers.customer_title,'Gender' from mss_customers
        Where 
            mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND mss_customers.customer_title = ".$this->db->escape($data['select_option'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleBirthday($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,mss_customers.customer_dob,'Birthday' from mss_customers
        where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ANd mss_customers.customer_dob BETWEEN ".$this->db->escape($data['start_range_date'])." AND ".$this->db->escape($data['end_range_date'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleAnniversary($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,mss_customers.customer_doa,'Anniversary' from mss_customers
        where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ANd mss_customers.customer_doa BETWEEN ".$this->db->escape($data['start_range_date'])." AND ".$this->db->escape($data['end_range_date'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleAge($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,mss_customers.customer_dob,'Age' from mss_customers
        where 
        mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
        TIMESTAMPDIFF(YEAR,mss_customers.customer_dob,CURRENT_DATE) BETWEEN ".$this->db->escape($data['start_range'])." AND ".$this->db->escape($data['end_range'])."";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    	//Schemes and services
	public function AddSchemeServices($data,$where){
		// $this->PrintArray($data);

		$result = $this->Insert($data,'mss_deals_discount');
		$last_insert_id = $result['res_arr']['insert_id'];
		
		if(isset($_POST['tag_name'])){
				if($_POST['tag_name']=="All"){
					$condition=array(
						'customer_business_outlet_id'=>$where['business_outlet_id'],
						'customer_business_admin_id'=> $where['business_admin_id']
					);
					$cust=$this-> MultiWhereSelect('mss_customers',$condition);
					$cust=$cust['res_arr'];
					
					foreach($cust as $cust){
						$data=array(
							'deal_id'=> $last_insert_id,
							'customer_id'=>$cust['customer_id']
						);
						$this->Insert($data,'mss_customer_tag_data');
					}

				}elseif($_POST['tag_name']=="New"){
					$condition=array(
						'customer_business_outlet_id'=>$where['business_outlet_id'],
						'customer_business_admin_id'=> $where['business_admin_id']
					);
					$cust=$this-> NewCustomer('mss_customers',$condition);
					$cust=$cust['res_arr'];
					
					foreach($cust as $cust){
						$data=array(
							'deal_id'=> $last_insert_id,
							'customer_id'=>$cust['txn_customer_id']
						);
						$this->Insert($data,'mss_customer_tag_data');
					}
				}elseif($_POST['tag_name']=="Repeat"){
					$condition=array(
						'r1'=>2,
						'r2'=> 5
					);
					$cust=$this-> RepeatingCustomerService($condition);
					$cust=$cust['res_arr'];
					// $this->PrintArray($cust);
					foreach($cust as $cust){
						$data=array(
							'deal_id'=> $last_insert_id,
							'customer_id'=>$cust['txn_customer_id']
						);
						$this->Insert($data,'mss_customer_tag_data');
					}
				}elseif($_POST['tag_name']=="Regular"){
					$condition=array(
						'regular_cust'=> 5
					);
					$cust=$this-> RegularCustomer($condition);
					$cust=$cust['res_arr'];

					foreach($cust as $cust){
						$data=array(
							'deal_id'=> $last_insert_id,
							'customer_id'=>$cust['txn_customer_id']
						);
						$this->Insert($data,'mss_customer_tag_data');
					}
				}elseif($_POST['tag_name']=="Risk"){
					$condition=array(
						'at_risk_cust'=> 30
					);
					$cust=$this-> RiskCustomerService($condition);
					$cust=$cust['res_arr'];
					
					foreach($cust as $cust){
						$data=array(
							'deal_id'=> $last_insert_id,
							'customer_id'=>$cust['customer_id']
						);
						$this->Insert($data,'mss_customer_tag_data');
					}
				}elseif($_POST['tag_name']=="Lost"){
					$condition=array(
						'lost_customer'=> 90
					);
					$cust=$this-> LostCustomerService($condition);
					$cust=$cust['res_arr'];
					
					foreach($cust as $cust){
						$data=array(
							'deal_id'=> $last_insert_id,
							'customer_id'=>$cust['customer_id']
						);
						$this->Insert($data,'mss_customer_tag_data');
					}
				}else{
					$where1=array(
						'tag_id'=>$_POST['tag_name']
					);
					$tag_info=$this->MultiWhereSelect('mss_tags_rule',$where1);
					$tag_info=$tag_info['res_arr'];
					// $this->PrintArray($tag_info);
					//Add Customer To Customer_tag_data table
					foreach($tag_info as $tag_info){
    					if($tag_info['kpi'] == 'total_amt_spent'){
    						$condition=array(
    							'start_range'	=> $tag_info['start_range'],
    							'end_range'		=> $tag_info['end_range']
    						);			
    						$cust=$this->RuleTotalAmountSpent($condition);
    						$cust=$cust['res_arr'];
    						foreach($cust as $cust){
    							$data=array(
    								'deal_id'=> $last_insert_id,
    								'customer_id'=>$cust['txn_customer_id']
    							);
    							$this->Insert($data,'mss_customer_tag_data');
    						}
    					// $this->PrintArray($cust);
    					}elseif ($tag_info['kpi'] == 'visits') {
    						$condition=array(
    							'start_range'	=> $tag_info['start_range'],
    							'end_range'		=> $tag_info['end_range']
    						);
    						$cust=$this->RuleVisits($condition);
    						$cust=$cust['res_arr'];
    						foreach($cust as $cust){
    							$data=array(
    								'deal_id'=> $last_insert_id,
    								'customer_id'=>$cust['txn_customer_id']
    							);
    							$this->Insert($data,'mss_customer_tag_data');
    						}
    						
    					}elseif ($tag_info['kpi'] == 'last_visit_date') {
    						$condition=array(
    							'date'	=> $tag_info['date']
    						);
    						$cust= $this->RuleLastVisitDate($condition);
    						$cust=$cust['res_arr'];
    						foreach($cust as $cust){
    							$data=array(
    								'deal_id'=> $last_insert_id,
    								'customer_id'=>$cust['txn_customer_id']
    							);
    							$this->Insert($data,'mss_customer_tag_data');
    						}
    					}elseif ($tag_info['kpi'] == 'points') {
    						$condition=array(
    							'start_range'	=> $tag_info['start_range'],
    							'end_range'		=> $tag_info['end_range']
    						);			
    						$cust=$this->RulePoints($condition);
    						$cust=$cust['res_arr'];
    						foreach($cust as $cust){
    							$data=array(
    								'deal_id'=> $last_insert_id,
    								'customer_id'=>$cust['customer_id']
    							);
    							$this->Insert($data,'mss_customer_tag_data');
    						}
    						
    					}elseif ($tag_info['kpi'] == 'gender') {
    						$condition=array(
    							'select_option'	=> $tag_info['select_option']
    						);
    						$cust= $this->RuleGender($condition);
    						$cust=$cust['res_arr'];
    						// $this->PrintArray($cust);
    						foreach($cust as $cust){
    							$data=array(
    								'deal_id'=> $last_insert_id,
    								'customer_id'=>$cust['customer_id']
    							);
    							$this->Insert($data,'mss_customer_tag_data');
    						}
    
    					}elseif ($tag_info['kpi'] == 'age') {
    						$condition=array(
    							'start_range'	=> $tag_info['start_range'],
    							'end_range'		=> $tag_info['end_range']
    						);			
    						$cust=$this->RuleAge($condition);
    						$cust=$cust['res_arr'];
    						foreach($cust as $cust){
    							$data=array(
    								'deal_id'=> $last_insert_id,
    								'customer_id'=>$cust['customer_id']
    							);
    							$this->Insert($data,'mss_customer_tag_data');
    						}
    						
    					}elseif ($tag_info['kpi'] == 'package_subscriber') {
							$condition=array(
								'start_range'	=> $tag_info['start_range'],
								'end_range'		=> $tag_info['end_range']
							);			
							$cust=$this->RulePackageSubscriber($condition);
							$cust=$cust['res_arr'];
							// $this->PrintArray($cust);
							foreach($cust as $cust){
								$data=array(
									'deal_id'=> $last_insert_id,
									'customer_id'=>$cust['customer_id']
								);
								$this->Insert($data,'mss_customer_tag_data');
							}
							// return $this->RulePackageSubscriber($data); 
						}elseif ($tag_info['kpi'] == 'avg_order') {
							$condition=array(
								'value'	=> $tag_info['value']
							);			
							$cust=$this->RuleAverageOrder($condition);
							$cust=$cust['res_arr'];
							// $this->PrintArray($cust);
							foreach($cust as $cust){
								$data=array(
									'deal_id'=> $last_insert_id,
									'customer_id'=>$cust['txn_customer_id']
								);
								$this->Insert($data,'mss_customer_tag_data');
							}
							// return $this->RuleAverageOrder($data); 
						}elseif ($tag_info['kpi'] == 'billing_date') {
                            $condition=array(
								'start_range_date'	=> $tag_info['start_range_date'],
								'end_range_date'		=> $tag_info['end_range_date']
							);			
							$cust=$this->RuleBillingDate($condition);
							$cust=$cust['res_arr'];
							foreach($cust as $cust){
								$data=array(
									'deal_id'=> $last_insert_id,
									'customer_id'=>$cust['txn_customer_id']
								);
								$this->Insert($data,'mss_customer_tag_data');
							}
							// return $this->RuleBillingDate($data); 
						}elseif ($tag_info['kpi'] == 'birthday') {
                            $condition=array(
								'start_range_date'	=> $tag_info['start_range_date'],
								'end_range_date'		=> $tag_info['end_range_date']
							);			
							$cust=$this->RuleBirthday($condition);
							$cust=$cust['res_arr'];
							foreach($cust as $cust){
								$data=array(
									'deal_id'=> $last_insert_id,
									'customer_id'=>$cust['customer_id']
								);
								$this->Insert($data,'mss_customer_tag_data');
							}
							// return $this->RuleBirthday($data); 
						}elseif ($tag_info['kpi'] == 'anniversary') {
                            $condition=array(
								'start_range_date'	=> $tag_info['start_range_date'],
								'end_range_date'		=> $tag_info['end_range_date']
							);			
							$cust=$this->RuleAnniversary($condition);
							$cust=$cust['res_arr'];
							foreach($cust as $cust){
								$data=array(
									'deal_id'=> $last_insert_id,
									'customer_id'=>$cust['customer_id']
								);
								$this->Insert($data,'mss_customer_tag_data');
							}
							// return $this->RuleAnniversary($data); 
						}elseif ($tag_info['kpi'] == 'package_expiry') {
                            $condition=array(
								'value'	=> $tag_info['value']
							);			
							$cust=$this->RulePackageExpiry($condition);
							$cust=$cust['res_arr'];
							// $this->PrintArray($cust);
							foreach($cust as $cust){
								$data=array(
									'deal_id'=> $last_insert_id,
									'customer_id'=>$cust['customer_id']
								);
								$this->Insert($data,'mss_customer_tag_data');
							}
							// return $this->RulePackageExpiry($data); 
						}else{
							$this->ModelHelper(false,true,"No Such KPI Exist!");
						}
					}
				}
				// $res=$this->AddTagCustomer();
			}
		
        if(!empty($_POST['category_type1'])){
            for($i=0;$i<count($_POST['category_type1']);$i++){
                $filter=array(
                    'category_type'=>$_POST['category_type1'][$i],
                    'min_price'=>$_POST['min_price1'][$i],
                    'max_price'=>$_POST['max_price1'][$i],
                    'business_admin_id'=>$where['business_admin_id'],
                    'business_outlet_id'=>$where['business_outlet_id']
                );
                // $categories=$this->ServiceByPrice($co);
                $result_2=$this->ServiceBetweenPrice($filter);
                $result_2=$result_2['res_arr'];
                // echo $result_2[1]['service_id'];
                // exit;
                for($k=0;$k< count($result_2);$k++){
                    $data_2 = array(
                    'deal_id' => $last_insert_id,
                    'service_id' => $result_2[$k]['service_id'],
                    'service_count' => $_POST['total_service']
                    );							
                    $result = $this->Insert($data_2,'mss_deals_data');            
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
                    'deal_id' => $last_insert_id,
                    'service_id' => $result_3[$k]['service_id'],
                    'service_count' => $_POST['total_service']
                    );							
                    $result_2 = $this->Insert($data_3,'mss_deals_data');            
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
                    'deal_id' => $last_insert_id,
                    'service_id' => $result_3[$k]['service_id'],
                    'service_count' => $_POST['total_service']
                    );							
                    $result_2 = $this->Insert($data_3,'mss_deals_data');            
                }
            }
        }
        if(!empty($_POST['special_service_id4'])){
            for($i=0;$i< count($_POST['special_service_id4']);$i++){	
                $data_3 = array(
                    'deal_id' => $last_insert_id,
                    'service_id' => $_POST['special_service_id4'][$i],
                    'service_count' => $_POST['total_service']
                );							
                $result_2 = $this->Insert($data_3,'mss_deals_data');                 
            }
        }
		// $this->PrintArray($temp);
		// exit;
        return $this->ModelHelper(true,false);
	}
	
	//Active Deals
	public function ActiveDeals($where){
        $sql = "SELECT 
			*
		FROM
			mss_deals_discount
		WHERE
			mss_deals_discount.deal_business_admin_id=".$this->db->escape($where['business_admin_id'])." AND
			mss_deals_discount.end_date >= CURRENT_DATE ";
        //execute the query
        $query = $this->db->query($sql);
        
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
	}
	
	//Active Deals
	public function GetTag($where){
        $sql = "SELECT 
			*
		FROM
			mss_tags_rule
		WHERE
			mss_tags_rule.business_admin_id=".$this->db->escape($where['business_admin_id'])." GROUP BY mss_tags_rule.tag_id ";
        //execute the query
        $query = $this->db->query($sql);
        
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
	}
	
	//04-05-2020
    public function ServicesAll($where){
        $sql = "SELECT * FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C 
        WHERE A.category_id = B.sub_category_category_id
        AND B.sub_category_id = C.service_sub_category_id 
        AND A.category_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
        AND C.service_is_active = 1
        AND A.category_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
        AND C.service_name LIKE '%$where%'"
        ;
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
    }
    public function RecommendeServiceProduct(){
        $sql = "SELECT mss_services.service_name,mss_recommended_services.* FROM `mss_recommended_services`,mss_services
        where mss_recommended_services.service_id=mss_services.service_id
        AND mss_recommended_services.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_services.service_is_active=1
        ";
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
    }
    public function EditRecommendeServiceProduct($data){
        $sql = "SELECT mss_services.service_name,mss_recommended_services.* FROM mss_recommended_services,mss_services
        where mss_recommended_services.service_id=mss_services.service_id
        AND mss_recommended_services.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_recommended_services.id=$data
        ";
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
    }
    // 05-05-2020
    public function GetTagsIDDetails($data){
        $sql="SELECT * from mss_tags_rule Where tag_id = $data group by tag_id ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    //09-05-2020
    public function CheckweekOffDeclared($data){
        $sql="SELECT * from mss_emss_weekoff Where month =".$this->db->escape($data['month'])." AND employee_id =".$this->db->escape($data['employee_id'])."";
          $query = $this->db->query($sql);
          if($query->num_rows() == 1){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function GetEmployee($data,$month){
        // echo gettype($month);
        $sql="SELECT * from mss_emss_weekoff Where employee_id = $data AND month = ".$this->db->escape($month)."";
          $query = $this->db->query($sql);
          if($query->num_rows() == 1 || $query->num_rows() > 1){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function GetLastDataInserted($data){
        // echo gettype($month);
        $sql="SELECT MAX(employee_id) as 'employee_id' from mss_employees";
          $query = $this->db->query($sql);
          if($query->num_rows() == 1 || $query->num_rows() > 1){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function GetCalWeekOff($data){
        $sql="SELECT employee_id,weekoff FROM mss_emss_weekoff where business_outlet_id=".$this->session->userdata['outlets']['current_outlet']." AND Month='06-2020'";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    } 
    public function GetCalWeekOffMonth($data)
    {
        $sql="SELECT employee_id,weekoff FROM mss_emss_weekoff where business_outlet_id=".$this->session->userdata['outlets']['current_outlet']." AND Month=".$this->db->escape($data['month'])."";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function GetCalWeekOffMonthSal($data){
        $sql="SELECT weekoff FROM mss_emss_weekoff where employee_id=".$this->db->escape($data['expert_id'])."
        AND
         month=".$this->db->escape($data['month_name'])."";
        $query = $this->db->query($sql);
        if($query->num_rows() == 1 ){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    //12-05-2020
    public function DRuleAverageOrder($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,sum(mss_transactions_replica.txn_value)/count(mss_transactions_replica.txn_id),'Avg Order' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions_replica.txn_customer_id
        HAVING sum(mss_transactions_replica.txn_value)/count(mss_transactions_replica.txn_id) =  ".$this->db->escape($data['value'])."
     ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function DRuleBillingDate($data){
        $sql="Select mss_customers.customer_name,mss_customers.customer_mobile,MAx(date(mss_transactions_replica.txn_datetime)),'Billing Date' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND date(mss_transactions_replica.txn_datetime) BETWEEN ".$this->db->escape($data['start_range_date'])." AND ".$this->db->escape($data['end_range_date'])."
        GROUP BY mss_transactions_replica.txn_customer_id
     ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    } 
    //15-05-2020
     // Revenue Client
    public function LastSevenDaysSalesClient(){
        $query="SELECT sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id) as total_sales ,
        date(mss_transactions.txn_datetime) as bill_date,
        mss_customers.customer_id
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
          mss_transactions.txn_customer_id IN (SELECT mss_transactions.txn_customer_id FROM mss_transactions GROUP BY mss_transactions.txn_id HAVING count(mss_transactions.txn_customer_id) = 1)
          AND
          mss_transactions.txn_customer_id = mss_customers.customer_id
          AND
          mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
          AND
          mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
          AND
          mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
          AND
          mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
          GROUP BY
            date(mss_transactions.txn_datetime)
          ORDER BY
            date(mss_transactions.txn_datetime)
          LIMIT 7";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
      }
    // Revenue Visit
    public function LastSevenDaysSales(){
      $query="SELECT sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id) as total_sales ,
      date(mss_transactions.txn_datetime) as bill_date
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
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
        GROUP BY
          date(mss_transactions.txn_datetime)
        ORDER BY
          date(mss_transactions.txn_datetime)
        LIMIT 7";
      
      $sql = $this->db->query($query);
      if ($sql->num_rows() > 0) {
        return $this->ModelHelper(true, false, '', $sql->result_array());
      } else {
        return $this->ModelHelper(false, true, 'DB Error!');
      }
    } 
    // revenue per day
    public function LastSevenDaysSalesPerDay(){
        $query="SELECT sum(mss_transaction_services.txn_service_discounted_price) as total_sales ,
        date(mss_transactions.txn_datetime) as bill_date
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
          mss_transactions.txn_customer_id = mss_customers.customer_id
          AND
          mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
          AND
          mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
          AND
          mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
          AND
          mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
          GROUP BY
            date(mss_transactions.txn_datetime)
          ORDER BY
            date(mss_transactions.txn_datetime)
          LIMIT 7";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    // service revenue per day
    public function LastSevenDaysServiceSalesPerDay(){
        $query="SELECT sum(mss_transaction_services.txn_service_discounted_price) as total_sales ,
        date(mss_transactions.txn_datetime) as bill_date
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
          mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
          AND
          mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
          AND
          mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
          GROUP BY
            date(mss_transactions.txn_datetime)
          ORDER BY
            date(mss_transactions.txn_datetime)
          LIMIT 7";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    // Product Revenue per Day
    public function LastSevenDaysProductSalesPerDay(){
        $query="SELECT sum(mss_transaction_services.txn_service_discounted_price) as total_sales ,
        date(mss_transactions.txn_datetime) as bill_date
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
          mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
          AND
          mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
          GROUP BY
            date(mss_transactions.txn_datetime)
          ORDER BY
            date(mss_transactions.txn_datetime)
          LIMIT 7";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    //Client
        // 7days Avg
        public function Client7DaysAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_customer_id),2) as total_sales
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
              mss_transactions.txn_customer_id IN (SELECT mss_transactions.txn_customer_id FROM mss_transactions GROUP BY mss_transactions.txn_id HAVING count(mss_transactions.txn_customer_id) = 1)
              AND
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
               mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
             
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // LMTD Client Avg
        public function Client7DaysAvgLMTD(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id),2) as total_sales
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
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              Month(mss_transactions.txn_datetime) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
              AND Year(mss_transactions.txn_datetime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // MTD Visit Avg
        public function Client7DaysAvgMTD(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id),2) as total_sales
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
              mss_transactions.txn_customer_id IN (SELECT mss_transactions.txn_customer_id FROM mss_transactions GROUP BY mss_transactions.txn_id HAVING count(mss_transactions.txn_customer_id) = 1)
              AND
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
             
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        } 
    // Visits
        // 7Days Avg
        public function Vist7DaysAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id),2) as total_sales
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
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // LMTD Visit Avg
        public function Vist7DaysAvgLMTD(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id),2) as total_sales
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
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              Month(mss_transactions.txn_datetime) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
              AND Year(mss_transactions.txn_datetime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // MTD Visit Avg
        public function Vist7DaysAvgMTD(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/count(mss_transactions.txn_id),2) as total_sales
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
              mss_transactions.txn_customer_id = mss_customers.customer_id
              AND
              mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
              AND
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    //Revenue Per Day
        //Revenue 7 Days Avg 
        public function Revenue7DaysAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/7,2) as total_sales 
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
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
            mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
            ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
            return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // MTD Renue Avg
        public function RevenueMTDAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/DAY(CURRENT_DATE),2) as total_sales 
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
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
            date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
            ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
            return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // LMTD Revenue Avg
        public function RevenueLMTDAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/DAY(LAST_DAY(CURDATE() - INTERVAL 1 MONTH)),2) as total_sales 
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
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
            Month(mss_transactions.txn_datetime) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
            AND 
            Year(mss_transactions.txn_datetime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
            ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
            return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    //Service Revenue Avg
        // Service 7 dAYS Avgs
        public function Service7DaysAvg(){
            $query="SELECT ROUND(sum(mss_transaction_services.txn_service_discounted_price)/7,2) as total_sales
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
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // MTD Service Avg
        public function ServiceMTDAvg(){
            $query="SELECT ROUND(sum(mss_transaction_services.txn_service_discounted_price)/DAY(CURRENT_DATE),2) as total_sales
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
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // LTD Service Avg
        public function ServiceLMTDAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/DAY(LAST_DAY(CURDATE() - INTERVAL 1 MONTH)),2) as total_sales 
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
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              Month(mss_transactions.txn_datetime) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
              AND 
              Year(mss_transactions.txn_datetime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    //Product Revenue Avg
        //Product 7 Days Avgs 
        public function Product7DaysAvg(){
            $query="SELECT ROUND(sum(mss_transaction_services.txn_service_discounted_price)/7,2) as total_sales 
        
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
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
               mss_transactions.txn_datetime >= date_sub(current_date,interval 7 day)
             
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // MTD Product Avg
        public function ProductMTDAvg(){
            $query="SELECT ROUND(sum(mss_transaction_services.txn_service_discounted_price)/DAY(CURRENT_DATE),2) as total_sales
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
              mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
              AND
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
              date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // LTD Product Avg
        public function ProductLMTDAvg(){
            $query="SELECT Round(sum(mss_transaction_services.txn_service_discounted_price)/DAY(LAST_DAY(CURDATE() - INTERVAL 1 MONTH)),2) as total_sales 
        
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
              mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
              AND
                  Month(mss_transactions.txn_datetime) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
                  AND 
                  Year(mss_transactions.txn_datetime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
             
             ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    //Product Penetration Trends
    // Product Retails Buyers
    public function RetailProduct3Months(){
        $query="SELECT count(mss_transactions.txn_id) as 'Buyers',DATE_FORMAT(mss_transactions.txn_datetime,'%m-%Y') as 'month'
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
        mss_services.inventory_type='Retail Product'
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH  AND date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
        GROUP BY
          MONTH(date(mss_transactions.txn_datetime))
        ORDER BY
          MONTH(date(mss_transactions.txn_datetime))
      ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    } 
        //product retail buyers mtd
        public function RetailProductMTD(){
            $query="SELECT count(mss_transactions.txn_id) as 'Buyers'
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
            mss_services.inventory_type='Retail Product'
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
            date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
          ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // product retail buyers Perc
        public function RetailProductMTDP(){
            $query="SELECT count(mss_transactions.txn_id) as 'Buyers'
            FROM
            mss_business_outlets,
            mss_business_admin,
            mss_transactions,
            mss_customers
            WHERE
            
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
            date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
          
          ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    //Package Buyers
    public function Package3Months(){
        $query="SELECT count(mss_package_transactions.package_txn_id) as 'Buyers',DATE_FORMAT(mss_package_transactions.datetime,'%m-%Y') as 'month'
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
            mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
            mss_package_transactions.datetime BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH  AND date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
        GROUP BY 
            month(mss_package_transactions.datetime)
        ORDER BY
            month(mss_package_transactions.datetime)
      ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
        // package buyers mtd
        public function PackageMTD(){
            $query="SELECT count(mss_package_transactions.package_txn_id) as 'Buyers'
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
                mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
                date(mss_package_transactions.datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
          ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
        // package buyers Perc
        public function PackageMTDP(){
            $query="SELECT count(mss_package_transactions.package_txn_id) as 'Buyers'
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
                mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
                date(mss_package_transactions.datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
          
          ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    //Product Sales Ratio
    public function Product3Months(){
        $query="SELECT count(mss_transactions.txn_id) as 'Buyers',DATE_FORMAT(mss_transactions.txn_datetime,'%m-%Y') as 'month'
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
        mss_services.service_type='otc'
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH  AND date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
        GROUP BY
          MONTH(date(mss_transactions.txn_datetime))
        ORDER BY
          MONTH(date(mss_transactions.txn_datetime))
      ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function TransactionProduct3Months(){
        $query="SELECT count(mss_transactions.txn_id) as 'Buyers',DATE_FORMAT(mss_transactions.txn_datetime,'%m-%Y') as 'month'
        FROM
       	mss_transactions,
        mss_customers
        WHERE
		mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH  AND date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
        GROUP BY
          MONTH(date(mss_transactions.txn_datetime))
        ORDER BY
          MONTH(date(mss_transactions.txn_datetime))
      ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }  
        // product ratio
        public function ProductMTD(){
            $query="SELECT count(mss_transactions.txn_id) as 'Buyers'
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
            mss_services.service_type='otc'
            AND
            mss_transactions.txn_customer_id = mss_customers.customer_id
            AND
            mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
            AND
            mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
            AND
            mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
            AND
             date(mss_transactions.txn_datetime) BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE
           
          ";
            
            $sql = $this->db->query($query);
            if ($sql->num_rows() > 0) {
              return $this->ModelHelper(true, false, '', $sql->result_array());
            } else {
              return $this->ModelHelper(false, true, 'DB Error!');
            }
        }
    // Product Non Buyers
    public function ProductNonBuyers3Months(){
        $query="SELECT count(mss_transactions.txn_id) as 'Buyers',DATE_FORMAT(mss_transactions.txn_datetime,'%m-%Y') as 'month'
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
        mss_services.service_type!='otc'
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND
        mss_transactions.txn_datetime BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH  AND date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)
        GROUP BY
          MONTH(date(mss_transactions.txn_datetime))
        ORDER BY
          MONTH(date(mss_transactions.txn_datetime))
      ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
          return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
          return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function TransactionAllCust(){
        $query="SELECT DISTINCT (mss_transactions.txn_customer_id) From mss_transactions,mss_customers
        where mss_transactions.txn_customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }

    public function TransactionAllCustService(){
        $query="SELECT DISTINCT(mss_transactions.txn_customer_id) as 'Buyers'
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
        mss_services.service_type = 'Service'
        AND
        mss_transactions.txn_customer_id = mss_customers.customer_id
        AND
        mss_customers.customer_business_outlet_id = mss_business_outlets.business_outlet_id
        AND
        mss_business_outlets.business_outlet_business_admin = mss_business_admin.business_admin_id
        AND
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }

    public function TransactionAllCustOtc(){
        $query="SELECT DISTINCT(mss_transactions.txn_customer_id) as 'Buyers'
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
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function Transaction90CustOtc(){
        $query="SELECT DISTINCT(mss_transactions.txn_customer_id) as 'Buyers'
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
        mss_business_outlets.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND date(mss_transactions.txn_datetime) NOT BETWEEN CURRENT_DATE AND date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -90 Day)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }

    // Appointment Dashboard
    public function AppointmentPerDay(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust',mss_appointments.appointment_date as 'Date' 
        FROM mss_appointments,mss_customers
        Where 
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_date >=date_sub(current_date,interval 7 day)
        AND mss_appointments.appointment_status=1
        Group By date(mss_appointments.appointment_date)
        ORDER BY date(mss_appointments.appointment_date)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentMTD(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust' 
        FROM mss_appointments,mss_customers
        Where 
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
       AND mss_appointments.appointment_date  BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentLMTD(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust',mss_appointments.appointment_date as 'Date' 
        FROM mss_appointments,mss_customers
        Where 
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
       AND Month(mss_appointments.appointment_date) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
       AND YEAR(mss_appointments.appointment_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    // Appointment /cancellation
    public function AppointmentCancellation(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust',mss_appointments.appointment_date as 'Date' 
        FROM mss_appointments,mss_customers
        Where 
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_date >=date_sub(current_date,interval 7 day)
        AND mss_appointments.appointment_status=0
        Group By date(mss_appointments.appointment_date)
        ORDER BY date(mss_appointments.appointment_date)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentCancellationMTD(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust' 
        FROM mss_appointments,mss_customers
        Where 
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_appointments.appointment_status=0
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
       AND mss_appointments.appointment_date  BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentCancellationLMTD(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust',mss_appointments.appointment_date as 'Date' 
        FROM mss_appointments,mss_customers
        Where 
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_status=0
       AND Month(mss_appointments.appointment_date) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
       AND YEAR(mss_appointments.appointment_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    // Appoiintment Total Viusit
    public function AppointmentVisit(){
        $query="SELECT count(DISTINCT(mss_appointments.customer_id)) as 'Cust',mss_appointments.appointment_date as 'Date'
        FROM mss_appointments,mss_customers,mss_transactions
        Where
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_date >= date_sub(current_date,interval 7 day)
        AND mss_appointments.appointment_date = date(mss_transactions.txn_datetime)
        AND mss_appointments.customer_id=mss_transactions.txn_customer_id
        AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 7 day)
        Group By date(mss_appointments.appointment_date)
        ORDER BY date(mss_appointments.appointment_date)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentVisitMTD(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust'
        FROM mss_appointments,mss_customers,mss_transactions
        Where
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_date BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
        AND mss_appointments.appointment_date = date(mss_transactions.txn_datetime)
        AND mss_appointments.customer_id=mss_transactions.txn_customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
        
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentVisitLMTD(){
        $query="SELECT count(mss_appointments.customer_id) as 'Cust'
        FROM mss_appointments,mss_customers,mss_transactions
        Where
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND Month(mss_appointments.appointment_date) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
       	AND YEAR(mss_appointments.appointment_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
        AND mss_appointments.appointment_date = date(mss_transactions.txn_datetime)
        AND mss_appointments.customer_id=mss_transactions.txn_customer_id
        AND Month(date(mss_transactions.txn_datetime)) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
        AND Year(date(mss_transactions.txn_datetime)) = Year(CURRENT_DATE - INTERVAL 1 MONTH)
        
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    // Appointment No Show
    public function AppointmentNoShow(){
        $query="SELECT count(DISTINCT(mss_appointments.customer_id)) as 'Cust',mss_appointments.appointment_date as 'Date'
        FROM mss_appointments,mss_customers,mss_transactions
        Where
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_date >= date_sub(current_date,interval 7 day)
        AND mss_appointments.customer_id != mss_transactions.txn_customer_id
        AND date(mss_transactions.txn_datetime) >= date_sub(current_date,interval 7 day)
        Group By date(mss_appointments.appointment_date)
        ORDER BY date(mss_appointments.appointment_date)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentShowMTD(){
        $query="SELECT count(DISTINCT(mss_appointments.customer_id)) as 'Cust'
        FROM mss_appointments,mss_customers,mss_transactions
        Where
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_appointments.appointment_date BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
        AND mss_appointments.customer_id != mss_transactions.txn_customer_id
        AND date(mss_transactions.txn_datetime) BETWEEN DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW()))-1 DAY) AND CURRENT_DATE-1
        
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function AppointmentShowLMTD(){
        $query="SELECT count(DISTINCT(mss_appointments.customer_id)) as 'Cust'
        FROM mss_appointments,mss_customers,mss_transactions
        Where
        mss_appointments.customer_id=mss_customers.customer_id
        AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND Month(mss_appointments.appointment_date) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
       	AND YEAR(mss_appointments.appointment_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
        AND mss_appointments.customer_id != mss_transactions.txn_customer_id
        AND Month(date(mss_transactions.txn_datetime)) = Month(CURRENT_DATE - INTERVAL 1 MONTH)
        AND Year(date(mss_transactions.txn_datetime)) = Year(CURRENT_DATE - INTERVAL 1 MONTH)
        ";
        
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    //21-05-2020
    public function StockValue(){
        $query="SELECT sum(mrp*sku_count) as 'sum' FROM `mss_inventory` WHERE outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ";
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function StockDetails(){
        $query="SELECT mss_services.service_id, mss_services.service_name,
        mss_sub_categories.sub_category_name,
        mss_categories.category_name,
        mss_inventory.sku_size,
        mss_inventory.sku_count
        FROM mss_services,mss_inventory,mss_sub_categories,mss_categories
        WHERE
        mss_inventory.service_id = mss_services.service_id
        AND mss_services.service_sub_category_id=mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_inventory.outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ";
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function StockDetailDead($data){
        $query="SELECT mss_inventory_transaction.sku_count as 'dead_stock',
        date(mss_inventory_transaction.datetime) as 'entry_date',
        DATEDIFF(CURRENT_DATE,mss_inventory_transaction.datetime) as 'days',
        mss_inventory_transaction.sku_count * (mss_services.service_price_inr+(mss_services.service_price_inr*mss_services.service_gst_percentage/100)) as 'Total Revenue'
        FROM mss_inventory_transaction,mss_services
        WHERE
        mss_inventory_transaction.mss_service_id = mss_services.service_id
        AND mss_inventory_transaction.mss_service_id=$data
        AND mss_inventory_transaction.outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_inventory_transaction.datetime < (CURRENT_DATE - INTERVAL 60 DAY)
        ";
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function StockDetailSlow($data){
        $query="SELECT mss_inventory_transaction.sku_count as 'dead_stock',
        date(mss_inventory_transaction.datetime) as 'entry_date',
        DATEDIFF(CURRENT_DATE,mss_inventory_transaction.datetime) as 'days',
        mss_inventory_transaction.sku_count * (mss_services.service_price_inr+(mss_services.service_price_inr*mss_services.service_gst_percentage/100)) as 'Total Revenue'
        FROM mss_inventory_transaction,mss_services
        WHERE
        mss_inventory_transaction.mss_service_id = mss_services.service_id
        AND mss_inventory_transaction.mss_service_id=$data
        AND mss_inventory_transaction.outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_inventory_transaction.datetime BETWEEN (CURRENT_DATE - INTERVAL 60 DAY) AND (CURRENT_DATE - INTERVAL 30 DAY)
        ";
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function RuleLastVisitRange($data){
        $sql="Select mss_transactions_replica.txn_customer_id as 'cust_id' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id  
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions_replica.txn_customer_id
        HAVING MAX(date(mss_transactions_replica.txn_datetime)) BETWEEN (CURRENT_DATE - INTERVAL ".$this->db->escape($data['end_range'])." DAY) AND (CURRENT_DATE - INTERVAL ".$this->db->escape($data['start_range'])." DAY)
        ";
            $query = $this->db->query($sql);
            if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
            return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
    public function DRuleLastVisitRange($data){
        $sql="Select mss_customers.customer_id,mss_customers.customer_name,mss_customers.customer_mobile,MAX(date(mss_transactions_replica.txn_datetime)),'Last Visit Range' from mss_transactions_replica,mss_customers 
        WHERE 
            mss_transactions_replica.txn_customer_id=mss_customers.customer_id  
            AND mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        GROUP BY mss_transactions_replica.txn_customer_id
        HAVING MAX(date(mss_transactions_replica.txn_datetime)) BETWEEN (CURRENT_DATE - INTERVAL ".$this->db->escape($data['end_range'])." DAY) AND (CURRENT_DATE - INTERVAL ".$this->db->escape($data['start_range'])." DAY)
       ";
          $query = $this->db->query($sql);
          if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
          }
          else{
          return $this->ModelHelper(false,true,"DB error!");   
          } 
    }
    public function ServicesOtc($where){
        $sql = "SELECT * FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND A.category_business_admin_id = ".$this->db->escape($where['category_business_admin_id'])." AND C.service_is_active = ".$this->db->escape($where['service_is_active'])." AND A.category_business_outlet_id = ".$this->db->escape($where['category_business_outlet_id'])." AND C.service_type = ".$this->db->escape($where['service_type'])." AND C.service_id=".$this->db->escape($where['service_id'])."";
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
    }
    public function CalRuleCust($data){
        if($data['kpi'] == 'total_amt_spent'){
                return $this->RuleTotalAmountSpent($data);
        }   elseif ($data['kpi'] == 'visits') {
                return $this->RuleVisits($data);
        }   elseif ($data['kpi'] == 'last_visit_date') {
                return $this->RuleLastVisitDate($data);
        } elseif ($data['kpi'] == 'last_visit_range') {
            return $this->RuleLastVisitRange($data);
        }  elseif ($data['kpi'] == 'points') {
                return $this->RulePoints($data); 
        }   elseif ($data['kpi'] == 'gender') {
                return $this->RuleGender($data); 
        }   elseif ($data['kpi'] == 'age') {
                return $this->RuleAge($data); 
        }   elseif ($data['kpi'] == 'package_subscriber') {
                return $this->RulePackageSubscriber($data); 
        }   elseif ($data['kpi'] == 'avg_order') {
                return $this->RuleAverageOrder($data); 
        }   elseif ($data['kpi'] == 'billing_date') {
                return $this->RuleBillingDate($data); 
        }   elseif ($data['kpi'] == 'birthday') {
                return $this->RuleBirthday($data); 
        }   elseif ($data['kpi'] == 'anniversary') {
                return $this->RuleAnniversary($data); 
        }   elseif ($data['kpi'] == 'package_expiry') {
                return $this->RulePackageExpiry($data); 
        }   else{
            $this->ModelHelper(false,true,"No such report exists!");
        }  
    }
    
    public function SubCategoriesOtc(){
        $sql = "SELECT mss_sub_categories.* FROM mss_categories,
		mss_sub_categories
        WHERE 
		mss_sub_categories.sub_category_category_id = mss_categories.category_id AND
		mss_sub_categories.sub_category_is_active=1 AND
		mss_categories.category_is_active=1 AND
		mss_categories.category_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']." ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    //26-05-2020
    public function StockDetailRegular($data){
        $query="SELECT mss_inventory_transaction.sku_count as 'dead_stock',
        date(mss_inventory_transaction.datetime) as 'entry_date',
        DATEDIFF(CURRENT_DATE,mss_inventory_transaction.datetime) as 'days',
        mss_inventory_transaction.sku_count * (mss_services.service_price_inr+(mss_services.service_price_inr*mss_services.service_gst_percentage/100)) as 'Total Revenue'
        FROM mss_inventory_transaction,mss_services
        WHERE
        mss_inventory_transaction.mss_service_id = mss_services.service_id
        AND mss_inventory_transaction.mss_service_id=$data
        AND mss_inventory_transaction.outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        AND mss_inventory_transaction.datetime BETWEEN (CURRENT_DATE - INTERVAL 30 DAY) AND (CURRENT_DATE)
        ";
        $sql = $this->db->query($query);
        if ($sql->num_rows() > 0) {
            return $this->ModelHelper(true, false, '', $sql->result_array());
        } else {
            return $this->ModelHelper(false, true, 'DB Error!');
        }
    }
    public function InventoryStock(){
        $sql = "SELECT 
		mss_services.service_id,
		mss_services.service_name,
		mss_services.inventory_type,
		mss_categories.category_name,
		mss_sub_categories.sub_category_name,
		mss_services.qty_per_item,
		mss_services.service_unit,
		SUM(mss_inventory.sku_count) AS 'Total'
	FROM 
		mss_inventory,
		mss_services,
		mss_sub_categories,
		mss_categories
	WHERE 
		mss_inventory.service_id = mss_services.service_id AND
		mss_services.service_sub_category_id = mss_sub_categories.sub_category_id AND
		mss_sub_categories.sub_category_category_id = mss_categories.category_id AND
        mss_categories.category_business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']." AND
		mss_categories.category_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
		GROUP BY mss_services.service_id ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	
	public function GetInventoryDetail($data){
        $sql = "SELECT * FROM mss_inventory WHERE mss_inventory.service_id=".$this->db->escape($data)." ORDER BY mss_inventory.id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
   //29-05-2020
   public function GetEmployeeSalary($month){
        // $this->PrettyPrintArray($month);
        $sql = "SELECT * FROM mss_emss_salary where mss_emss_salary.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']." AND mss_emss_salary.month=".$this->db->escape($month)."";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function VerifyEmployee($data){
        // $this->PrettyPrintArray($month);
        $sql = "SELECT * FROM mss_emss_salary where mss_emss_salary.business_outlet_id=".$this->session->userdata['outlets']['current_outlet']." AND mss_emss_salary.month=".$this->db->escape($data['month'])." AND mss_emss_salary.expert_id=".$this->db->escape($data['expert_id'])."";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // 05-06
    public function CustomerWithoutTransaction(){
        $sql="SELECT count( DISTINCT(mss_customers.customer_id)) AS count FROM 
		mss_customers
		WHERE
		mss_customers.customer_id NOT IN 
		(
			SELECT mss_transactions.txn_customer_id
			FROM
			mss_transactions,
			mss_employees
			  WHERE
			mss_transactions.txn_cashier = mss_employees.employee_id AND
			mss_employees.employee_business_outlet= ".$this->session->userdata['outlets']['current_outlet']."
		)
		AND
		mss_customers.customer_business_outlet_id = ".$this->session->userdata['outlets']['current_outlet']." ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function AllCustomerCount(){
        $sql="Select count(*) as count from mss_customers WHERE mss_customers.customer_business_outlet_id=".$this->session->userdata['outlets']['current_outlet']."
        ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    
    
    private function CancelledTransaction($data){
        $sql = "SELECT 
                    mss_transactions.txn_unique_serial_id AS 'Txn Unique Serial Id',
                    date(mss_transactions.txn_datetime) AS 'Billing Date',
                    mss_customers.customer_mobile AS 'Mobile No',
                    mss_customers.customer_id AS 'customer_id',
                    mss_customers.customer_name AS 'Customer Name',
                    (mss_transactions.txn_discount+mss_transactions.txn_value) AS 'MRP Amt',
                    mss_transactions.txn_discount AS 'Discount',
                    mss_transactions.txn_value AS 'Net Amount',
					mss_transactions.txn_status AS 'billed=1/canceled=0',
					mss_transactions.txn_remarks AS 'Remarks',
                    mss_transactions.txn_total_tax AS 'Total Tax (Rs.)',
                    mss_transactions.txn_pending_amount AS 'Pending Amount',
                    mss_transaction_settlements.txn_settlement_way AS 'Settlement Way',
                    mss_transaction_settlements.txn_settlement_payment_mode AS 'Payment Mode'
                    
                FROM 
                    mss_customers,
                    mss_transactions,
                    mss_transaction_settlements
                WHERE 
                    mss_transactions.txn_customer_id = mss_customers.customer_id
                    AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
                    AND mss_transactions.txn_status=0
                    AND mss_customers.customer_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
                    AND mss_customers.customer_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
                    AND date(mss_transactions.txn_datetime) BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])."";

        $query = $this->db->query($sql);
        
        if($query){
            $result = $query->result_array();
            $result_to_send = array();

            for($i=0;$i<count($result);$i++){
                array_push($result_to_send, $result[$i]);
                if($result[$i]["Settlement Way"] == "Split Payment" && $result[$i]["Payment Mode"] != "Split"){
                    $str = $result[$i]["Payment Mode"];
                    $someArray = json_decode($str, true);
                    $simpler_string = "{";
                    $len = count($someArray);
                    for($j=0;$j<$len;$j++){
                        if($j == ($len-1)){
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"];    
                        }
                        else{
                            $simpler_string .= $someArray[$j]["payment_type"] ." : ". $someArray[$j]["amount_received"] .",";
                        }
                    }
                    $simpler_string .= "}";
                    $result_to_send[$i]["Payment Mode"] =  $simpler_string;
                }             
            }

            return $this->ModelHelper(true,false,'',$result_to_send);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}

	private function EmployeeAttendanceReport($data){
        $sql = "SELECT 
					mss_employees.employee_first_name,
					mss_employees.employee_id,
					mss_emss_attendance.attendance_date,
					mss_emss_attendance.in_time,
					mss_emss_attendance.out_time,
					mss_emss_attendance.working_hours
				FROM
					mss_employees,
					mss_emss_attendance
				WHERE 
					mss_employees.employee_business_outlet=".$this->db->escape($data['business_admin_id'])." AND
					mss_employees.employee_business_admin=".$this->db->escape($data['business_outlet_id'])." AND 
					mss_emss_attendance.attendance_date BETWEEN ".$this->db->escape($data['from_date'])." AND ".$this->db->escape($data['to_date'])." ";

        $query = $this->db->query($sql);
        
        if($query){
            // $result = $query->result_array();
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	
    public function GetRawMaterialsIn($where){
        $sql = "SELECT 
		mss_services.*
	FROM 
		mss_services,
		mss_sub_categories,
		mss_categories
	WHERE 
		mss_services.inventory_type='Raw Material' AND
		mss_services.service_is_active=1 AND
		mss_services.service_sub_category_id =mss_sub_categories.sub_category_id AND
		mss_sub_categories.sub_category_category_id = mss_categories.category_id AND
		mss_categories.category_is_active=1 AND  
		mss_categories.category_business_outlet_id=".$this->db->escape($where['business_outlet_id'])."  AND
		mss_categories.category_business_admin_id=".$this->db->escape($where['business_admin_id'])."";
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
	}
	
	public function GetCustomerBill($where){
       $sql = "SELECT mss_transaction_cart.id as cart_id,mss_transactions.txn_value,
                date(mss_transactions.txn_datetime) AS 'date',
                mss_customers.customer_id,
                mss_customers.customer_name,
                mss_customers.customer_mobile,
                mss_business_outlets.business_outlet_sender_id AS 'sender_id',
                mss_business_outlets.api_key,
                mss_business_outlets.business_outlet_name,
                mss_transaction_cart.id 
            FROM
                mss_transactions,
                mss_customers,
                mss_business_outlets,
                mss_transaction_cart
            WHERE
                mss_customers.customer_id=mss_transactions.txn_customer_id AND
                mss_transaction_cart.transaction_id= mss_transactions.txn_id AND
                mss_business_outlets.business_outlet_id= ".$this->db->escape($where['business_outlet_id'])." AND 
                mss_transactions.txn_id= ".$this->db->escape($where['txn_id'])." group by mss_transactions.txn_id";
        
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
    }

    public function GetCustomerPackageBill($where){
        $sql = "SELECT mss_package_transactions.package_txn_value, mss_package_transactions.package_txn_unique_serial_id, Date(mss_package_transactions.datetime) AS 'date', mss_customers.customer_id, mss_customers.customer_name, mss_customers.customer_mobile, mss_business_outlets.business_outlet_sender_id AS 'sender_id', mss_business_outlets.api_key, mss_business_outlets.business_outlet_name, mss_transaction_cart.id FROM mss_package_transactions, mss_customers, mss_business_outlets, mss_transaction_cart WHERE mss_business_outlets.business_outlet_id =  ".$this->db->escape($where['business_outlet_id'])." AND mss_package_transactions.package_txn_id = ".$this->db->escape($where['txn_id'])." group by mss_package_transactions.package_txn_id";
        
         $query = $this->db->query($sql);
         if($query){
             return $this->ModelHelper(true,false,'',$query->result_array());
         }
         else{
             return $this->ModelHelper(false,true,"DB error!");   
         }
    }
	
	public function GetTransactionDetailByTxnId($where){
        $sql = "SELECT mss_customers.customer_name,
			mss_services.service_name,
			mss_services.service_price_inr,
    		mss_services.service_gst_percentage,
    		mss_transaction_services.txn_service_discount_percentage,
    		mss_transaction_services.txn_service_discount_absolute,
			mss_transaction_services.txn_service_quantity,
			mss_transaction_services.txn_service_discounted_price,
			mss_transaction_services.txn_add_on_amount,
			mss_transactions.txn_discount,
			mss_transactions.txn_value,
			date(mss_transactions.txn_datetime) AS 'date',
			mss_transactions.txn_unique_serial_id
		FROM
			mss_transactions,
			mss_transaction_services,
			mss_services,
			mss_customers
		WHERE 
			mss_transaction_services.txn_service_txn_id= mss_transactions.txn_id  AND
			mss_transaction_services.txn_service_service_id= mss_services.service_id AND
			mss_transactions.txn_customer_id= mss_customers.customer_id AND
			mss_transactions.txn_id= ".$this->db->escape($where)."
			GROUP BY mss_transaction_services.txn_service_id";
        
		$query = $this->db->query($sql);
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
    }


    public function GetPackageTransactionDetailByTxnId($txn_id){
        $sql = "SELECT 
    
    mss_package_transactions.datetime,
    mss_package_transactions.package_txn_cashier,
    mss_package_transactions.package_txn_unique_serial_id,
    mss_transaction_package_details.txn_package_price,    
    mss_salon_packages.salon_package_name AS 'package_name',
    mss_package_transactions.package_txn_discount AS 'Discount',
    mss_salon_packages.salon_package_upfront_amt AS 'package_old_price',    
    mss_package_transactions.package_txn_value AS 'package_final_value',
    mss_salon_packages.salon_package_price AS 'package_price_inr',
    mss_salon_packages.salon_package_validity AS 'package_validity',
    mss_salon_packages.service_gst_percentage AS 'package_gst',
    mss_salon_packages.service_gst_percentage AS 'package_old_gst',
    mss_salon_packages.salon_package_type AS 'package_type',
    mss_employees.employee_id,
    mss_employees.employee_first_name AS 'employee_name',
    mss_customers.customer_id,
    mss_customers.customer_name,
    mss_salon_packages.salon_package_id
FROM
    mss_package_transactions,
    mss_transaction_package_details,
    mss_salon_packages,
    mss_employees,
    mss_customers
WHERE
    mss_transaction_package_details.package_txn_id=mss_package_transactions.package_txn_id AND
    mss_transaction_package_details.salon_package_id= mss_salon_packages.salon_package_id AND
    mss_package_transactions.package_txn_expert= mss_employees.employee_id AND 
    mss_package_transactions.package_txn_customer_id= mss_customers.customer_id AND
    mss_package_transactions.package_txn_id=".$txn_id;
        
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

	public function GetDailyAmountPaidBAck($where){
        $sql = "SELECT 
		SUM(mss_transaction_settlements.txn_settlement_balance_paid) AS 'paid_back'
	FROM 
		mss_transactions, 
		mss_transaction_settlements, 
		mss_customers,
		mss_employees 
	WHERE 
		mss_transactions.txn_customer_id = mss_customers.customer_id 
		AND mss_transactions.txn_status=1
		AND mss_transactions.txn_cashier =mss_employees.employee_id 
		AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
		AND mss_transaction_settlements.txn_settlement_way = 'Split Payment'
		AND mss_employees.employee_business_admin = ".$this->db->escape($where['business_admin_id'])."
		AND mss_employees.employee_business_outlet = ".$this->db->escape($where['business_outlet_id'])."
		AND date(mss_transactions.txn_datetime) = date(now())";
        
		$query = $this->db->query($sql);
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
	}

	public function UpdateAbsDiscount($data){
        $sql = "UPDATE 
		mss_transaction_services,
		mss_transactions
		SET mss_transaction_services.txn_service_discount_absolute= ".$this->db->escape($data['txn_service_discount_absolute'])." ,
		mss_transaction_services.txn_service_discounted_price=(mss_transaction_services.txn_service_discounted_price+".$this->db->escape($data['txn_discounted_result'])."),
		mss_transactions.txn_discount= (mss_transactions.txn_discount-".$this->db->escape($data['txn_discounted_result'])."),
		mss_transactions.txn_value= (mss_transactions.txn_value+".$this->db->escape($data['txn_discounted_result']).")	
		WHERE mss_transaction_services.txn_service_id=".$this->db->escape($data['txn_service_id'])."AND 
		mss_transactions.txn_id=".$this->db->escape($data['txn_id'])." ";

		$query = $this->db->query($sql);
		if($query){
			return $this->ModelHelper(true,false,'');
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
	}
	

	public function UpdateCustomerPendingAmount($cust_id,$pending_amount){
        $sql = "UPDATE 
		mss_customers
		SET mss_customers.customer_pending_amount=(mss_customers.customer_pending_amount- $pending_amount)	
		WHERE mss_customers.customer_id=$cust_id ";

		$query = $this->db->query($sql);
		if($query){
			return $this->ModelHelper(true,false,'');
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
	}

    public function GetExpenseRecord($date){

        if(!empty($this->session->userdata['outlets']['current_outlet'])){
            $outlet_id = $this->session->userdata['outlets']['current_outlet'];
        }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
            $outlet_id = $this->session->userdata['logged_in']['business_outlet_id'];
        }        

        $sql = "SELECT payment_mode, SUM(amount) as total_amount FROM `mss_expenses` WHERE `expense_date` = '$date' and bussiness_outlet_id = ".$outlet_id." GROUP BY payment_mode";
        
        $query = $this->db->query($sql);
        $data['expenses'] = $query->result_array();


        $sql = "SELECT payment_type, SUM(pending_amount_submitted) as total_amount FROM `mss_pending_amount_tracker` WHERE DATE(`date_time`)  = '$date' and business_outlet_id = ".$outlet_id." GROUP BY payment_type";

        $query = $this->db->query($sql);
        $data['pending_amount'] = $query->result_array();

        $sql = 'SELECT t2.txn_settlement_payment_mode, 
       t2.txn_settlement_amount_received AS total_price 
FROM   `mss_transactions` t1 
       LEFT JOIN mss_transaction_settlements t2 
              ON t1.`txn_id` = t2.txn_settlement_txn_id 
       LEFT JOIN mss_employees t3 on t1.`txn_cashier` = t3.employee_id
WHERE  Date(t1.txn_datetime) = "'.$date.'" and t3.employee_business_outlet = '.$outlet_id.' GROUP  BY t2.txn_settlement_txn_id';
    // $sql = 'SELECT t2.txn_settlement_payment_mode, t2.txn_settlement_amount_received AS total_price FROM `mss_transactions` t1 LEFT JOIN mss_transaction_settlements t2 ON t1.`txn_id` = t2.txn_settlement_txn_id WHERE Date(t1.txn_datetime) = "2020-07-12" GROUP BY t2.txn_settlement_txn_id';

    // echo $sql;die;
        $query = $this->db->query($sql);
        $data['transaction'] = $query->result_array();

        if($data){
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetCronExpenseRecord($date){
        $sql = "SELECT payment_mode, (amount) as total_amount,bussiness_outlet_id FROM `mss_expenses` WHERE `expense_date` = '$date'";
        #\\echo $sql;die;
        $query = $this->db->query($sql);
        $data['expenses'] = $query->result_array();


        $sql = "SELECT payment_type, (pending_amount_submitted) as total_amount,business_outlet_id as bussiness_outlet_id FROM `mss_pending_amount_tracker` WHERE DATE(`date_time`)  = '$date'";
        //echo $sql;die;  
        $query = $this->db->query($sql);
        $data['pending_amount'] = $query->result_array();

        $sql = 'SELECT t2.txn_settlement_payment_mode, 
        t2.txn_settlement_amount_received AS total_price ,t3.employee_business_outlet as bussiness_outlet_id
        FROM   `mss_transactions` t1 
        LEFT JOIN mss_transaction_settlements t2 
        ON t1.`txn_id` = t2.txn_settlement_txn_id 
        LEFT JOIN mss_employees t3 on t1.`txn_cashier` = t3.employee_id
WHERE  Date(t1.txn_datetime) = "'.$date.'" GROUP  BY t2.txn_settlement_txn_id';


        $query = $this->db->query($sql);
        $data['transaction'] = $query->result_array();

        if($data){
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

     public function getOpeningRecord($date){
        if(!empty($this->session->userdata['outlets']['current_outlet'])){
            $outlet_id = $this->session->userdata['outlets']['current_outlet'];
        }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
            $outlet_id = $this->session->userdata['logged_in']['business_outlet_id'];
        }
    if(!empty($outlet_id)){
            $sql = "select * from mss_opening_balance where opening_date='".$date."' and business_outlet_id = ".$outlet_id;
        }else{
            $sql = "select * from mss_opening_balance where opening_date='".$date."'";
        }        $query = $this->db->query($sql);
        $data['opening_balance'] = $query->result_array();
        if($data){
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
	
public function GetCashRecord($from,$to){

        if(!empty($this->session->userdata['outlets']['current_outlet'])){
            $outlet_id = $this->session->userdata['outlets']['current_outlet'];
        }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
            $outlet_id = $this->session->userdata['logged_in']['business_outlet_id'];
        }        

        $sql = "SELECT payment_mode, SUM(amount) as total_amount FROM `mss_expenses` WHERE `expense_date` between '".$from."' AND '".$to."'  and bussiness_outlet_id = ".$outlet_id." GROUP BY payment_mode";

        $query = $this->db->query($sql);
        $data['expenses'] = $query->result_array();


        $sql = "SELECT payment_type, SUM(pending_amount_submitted) as total_amount FROM `mss_pending_amount_tracker` WHERE DATE(`date_time`)   between  '".$from."' AND '".$to."' and business_outlet_id = ".$outlet_id." GROUP BY payment_type";

        $query = $this->db->query($sql);
        $data['pending_amount'] = $query->result_array();

        $sql = 'SELECT t2.txn_settlement_payment_mode, 
        t2.txn_settlement_amount_received AS total_price 
FROM   `mss_transactions` t1 
        LEFT JOIN mss_transaction_settlements t2 
        ON t1.`txn_id` = t2.txn_settlement_txn_id 
        LEFT JOIN mss_employees t3 on t1.`txn_cashier` = t3.employee_id
WHERE  Date(t1.txn_datetime)  between "'.$from.'" AND "'.$to.'" and t3.employee_business_outlet = '.$outlet_id.' GROUP  BY t2.txn_settlement_txn_id';
    // $sql = 'SELECT t2.txn_settlement_payment_mode, t2.txn_settlement_amount_received AS total_price FROM `mss_transactions` t1 LEFT JOIN mss_transaction_settlements t2 ON t1.`txn_id` = t2.txn_settlement_txn_id WHERE Date(t1.txn_datetime) = "2020-07-12" GROUP BY t2.txn_settlement_txn_id';

    // echo $sql;die;
        $query = $this->db->query($sql);
        $data['transaction'] = $query->result_array();

        if($data){
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	

	public function GetPackageDetails($where){
        $sql = "SELECT *
		FROM 
			mss_salon_packages 
		WHERE 
		mss_salon_packages.salon_package_id = ".$this->db->escape($where['salon_package_id'])."
			";
        
		$query = $this->db->query($sql);
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
	}

	public function UpdateDiscountPackageForSalon($data,$services,$discounts,$count_discount,$salon_package_id){
		
		$result = $this->Update($data,'mss_salon_packages','salon_package_id');
        $count = 0;
        //create a discounts packages
        for($i=0;$i < count($services);$i++){
			$where=array(
					'salon_package_id' => $salon_package_id,
					'service_id'	=> (int)$services[$i]
			);
			$where2=array(
				'salon_package_id' => $salon_package_id
			);
			$is_service_exist=$this->MultiWhereSelect('mss_salon_package_data',$where);
			if(empty($is_service_exist['res_arr']) || $is_service_exist['res_arr']==""){
					$data_2 = array(
						'salon_package_id' => $salon_package_id,
						'service_id' => (int)$services[$i],
						'discount_percentage' => (int)$discounts[$i],
						'service_count' => (int)$count_discount[$i]
					);
					$result_2 = $this->Insert($data_2,'mss_salon_package_data');

					$cust_with_package = $this->MultiWhereSelect('mss_customer_packages',$where2);
					$cust_with_package=$cust_with_package['res_arr'];
					foreach($cust_with_package as $cust){
						$data_3=array(
							'customer_package_id'=> $cust['customer_package_id'],
							'service_id'=> (int)$services[$i],
							'service_count'=> (int)$count_discount[$i],
							'service_discount'=> (int)$discounts[$i]
						);
						$result_3 = $this->Insert($data_3,'mss_customer_package_profile');
					}
			}            
            if($result_2['success'] == 'true'){
                $count = $count + 1;
            }    
        }        
        if($count  <= count($services)){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,"Can't Update!");        
        }
	}
	
	public function EditServicePackageForSalon($data,$services,$count_service){		    
		$result = $this->Update($data,'mss_salon_packages','salon_package_id');
        $count = 0;
        //create a discounts packages
        for($i=0;$i < count($services);$i++){
			$where=array(
					'salon_package_id' => $data['salon_package_id'],
					'service_id'	=> (int)$services[$i]
			);
			$where2=array(
				'salon_package_id' => $data['salon_package_id']
			);
			$is_service_exist=$this->MultiWhereSelect('mss_salon_package_data',$where);
			if(empty($is_service_exist['res_arr']) || $is_service_exist['res_arr']==""){
					$data_2 = array(
						'salon_package_id' => $data['salon_package_id'],
						'service_id' => (int)$services[$i],
						'discount_percentage' => 100,
						'service_count' => (int)$count_service[$i]
					);
					$result_2 = $this->Insert($data_2,'mss_salon_package_data');

					$cust_with_package = $this->MultiWhereSelect('mss_customer_packages',$where2);
					$cust_with_package=$cust_with_package['res_arr'];
					foreach($cust_with_package as $cust){
						$data_3=array(
							'customer_package_id'=> $cust['customer_package_id'],
							'service_id'=> (int)$services[$i],
							'service_count'=> (int)$count_service[$i],
							'service_discount'=> 100
						);
						$result_3 = $this->Insert($data_3,'mss_customer_package_profile');
					}
			}            
            if($result_2['success'] == 'true'){
                $count = $count + 1;
            }    
        }        
        if($count  <= count($services)){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,"Can't Update!");        
        }
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CashierModel extends CI_Model {

    public function ModelHelper($success,$error,$error_msg = '',$data_arr = array()){
        if($success == true && $error == false){
            $data = array(
                'success' => 'true',
                'error'   => 'false',
                'message' => $error_msg,
                'res_arr' => $data_arr 
            ) ;
            
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

    //public function for logging in the Cashier to dashboard    
    public function CashierLogin($data) {
        $this->db->select('*');
        $this->db->from('mss_employees');
        $this->db->where('employee_email',$data['employee_email']);
        $this->db->limit(1);        
		$query = $this->db->get();
		// $sql="SELECT 
		// 	mss_employees.*
		// FROM
		// 	mss_employees,
		// 	mss_business_outlets
		// WHERE
		// 	mss_business_outlets.business_outlet_status=1
		// AND
		// 	mss_employees.employee_business_outlet=mss_business_outlets.business_outlet_id
		// ";
		// $query = $this->db->query($sql);
        if ($query->num_rows() == 1){
            log_message('Error','Successfull Login.'.$_SERVER['REMOTE_ADDR']);
            return $this->ModelHelper(true,false);
        }
        else{
            log_message('Error','Login fail.'.$_SERVER['REMOTE_ADDR']);
            return $this->ModelHelper(false,true,'No such employee exists !');
        }
    }
    

    public function CashierByEmail($email) {

        // $sql = "SELECT * 
		// FROM mss_employees AS A,
		// 	mss_business_admin AS B 
		// WHERE A.employee_email = ".$this->db->escape($email)." AND A.employee_business_admin = B.business_admin_id";
		$sql="SELECT 
			* 
		FROM 
			mss_employees AS A,
			mss_business_admin AS B,
			mss_business_outlets AS C
		WHERE 
			A.employee_email = ".$this->db->escape($email)." 
		AND 
			A.employee_business_admin = B.business_admin_id
		AND	
			A.employee_business_outlet= C.business_outlet_id
		AND 
			C.business_outlet_status=1 ";
			
        $query = $this->db->query($sql);
        
        if($query && $query->num_rows() > 0){
            return $this->ModelHelper(true,false,'',$query->row_array());
        }
        else{
            return $this->ModelHelper(false,true,"Your Outlet has been suspended. Contact Business Admin.");   
        }
    }


	 //Generic function which will give all details by primary key of table
    public function DetailsById($id,$table_name,$where){
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
    public function MultiWhereSingleSelect($table_name,$where_array){
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where_array);
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
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
        elseif($this->db->affected_rows() == 0){
            return $this->ModelHelper(true,false,"No row updated!");   
        }
        else{
            return $this->ModelHelper(false,true,"Some DB Error!");
        }    
        
    }

    //Generic function
    public function Insert($data,$table_name){
        if($this->db->insert($table_name,$data)){
            $data = array('insert_id' => $this->db->insert_id());
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"Check your inserted query!");
        }
    }


    public function GetPurchasedPackagesCategories($where){
        $sql = "SELECT DISTINCT mss_categories.category_name,mss_categories.category_id,mss_customer_packages.customer_id FROM mss_customer_packages,mss_customer_package_profile,mss_services,mss_sub_categories,mss_categories WHERE mss_customer_packages.customer_package_id = mss_customer_package_profile.customer_package_id AND mss_customer_package_profile.service_id = mss_services.service_id AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id AND mss_sub_categories.sub_category_category_id = mss_categories.category_id AND mss_customer_packages.customer_id = ".$this->db->escape($where['customer_id'])." AND mss_customer_packages.package_expiry_date > DATE(NOW())";
        
        //execute the query
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetPurchasedPackagesSubCategories($where){
        
        $sql = "SELECT 
                    DISTINCT mss_sub_categories.sub_category_name,
                    mss_sub_categories.sub_category_id,
                    mss_customer_packages.customer_id 
                FROM 
                    mss_customer_packages,
                    mss_customer_package_profile,
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE 
                    mss_customer_packages.customer_package_id = mss_customer_package_profile.customer_package_id 
                    AND mss_customer_package_profile.service_id = mss_services.service_id 
                    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_id = ".$this->db->escape($where['category_id'])."
                    AND mss_customer_packages.customer_id = ".$this->db->escape($where['customer_id'])."
                    AND mss_customer_packages.package_expiry_date > DATE(NOW())";

        //execute the query
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetPurchasedPackagesServices($where){
        
        $sql = "SELECT 
                    *
                FROM 
                    mss_customer_packages,
                    mss_customer_package_profile,
                    mss_services,
                    mss_salon_packages
                WHERE 
                    mss_customer_packages.customer_package_id = mss_customer_package_profile.customer_package_id 
                    AND mss_customer_package_profile.service_id = mss_services.service_id
                    AND mss_customer_packages.salon_package_id = mss_salon_packages.salon_package_id
                    AND mss_services.service_sub_category_id = ".$this->db->escape($where['sub_category_id'])."
                    AND mss_customer_packages.customer_id = ".$this->db->escape($where['customer_id'])." 
                    AND mss_customer_package_profile.service_count > 0
                    AND mss_customer_packages.package_expiry_date > DATE(NOW())";
                        
        //execute the query
		$query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetCashierPersonal($employee_id){
        $sql = "SELECT * FROM mss_employees,mss_business_outlets WHERE mss_employees.employee_business_outlet = mss_business_outlets.business_outlet_id AND mss_employees.employee_id = ".$this->db->escape($employee_id)."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->row_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function SearchCustomers($search_term,$business_admin_id,$business_outlet_id,$master_admin_id){

        $sql = "SELECT customer_id,customer_name,customer_mobile,customer_title FROM `mss_customers` WHERE customer_master_admin_id = ".$this->db->escape($master_admin_id)." AND (customer_name LIKE '%$search_term%' OR customer_mobile  LIKE '%$search_term%') ORDER BY customer_name LIMIT 15";
        
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

     public function SearchServices($search_term,$business_admin_id,$business_outlet_id){
        $sql = "SELECT 
                    ROUND(mss_services.service_price_inr+mss_services.service_price_inr*(mss_services.service_gst_percentage/100)) as 'mrp',
                    mss_services.*,
                    mss_categories.category_name
                FROM 
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($business_admin_id)."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($business_outlet_id)."
                    AND mss_services.service_is_active = 1
                    AND (mss_services.service_name LIKE '%$search_term%' OR mss_services.barcode LIKE '$search_term%')
                ORDER BY mss_services.service_name LIMIT 15";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function CheckCustomerExists($where){
        $this->db->select('*');
        $this->db->from('mss_customers');
        $this->db->where('customer_mobile',$where['customer_mobile']);
        // $this->db->where('customer_business_admin_id',$where['customer_business_admin_id']);
		// $this->db->where('customer_business_outlet_id',$where['customer_business_outlet_id']);
		$this->db->where('customer_master_admin_id',$where['customer_master_admin_id']);
        
        $query = $this->db->get();

        if ($query->num_rows() == 0){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'Customer already exists!');
        }
    }

    public function CheckCompositionExists($where){
        $this->db->select('*');
        $this->db->from('mss_raw_composition');
        $this->db->where('service_id',$where['service_id']);
        
        $query = $this->db->get();

        if ($query->num_rows() === 0){
            return $this->ModelHelper(false,true,"Composition do not exists!");
        }
        else if($query->num_rows() >= 1){
            return $this->ModelHelper(true,false,'Composition already exists!');
        }
    }

    public function CheckRmStockExists($where){
        $this->db->select('*');
        $this->db->from('mss_raw_material_stock');
        $this->db->where('rmc_id',$where['rmc_id']);
        
        $query = $this->db->get();

        if($query->num_rows() === 0){
            return $this->ModelHelper(false,true);
        }
        else if($query->num_rows() === 1){
            return $this->ModelHelper(true,false);
        }   
    }

    public function CheckOTCStockExists($where){
        $this->db->select('*');
        $this->db->from('mss_inventory');
        $this->db->where('service_id',$where['service_id']);
        
        $query = $this->db->get();

        if($query->num_rows() === 0){
            return $this->ModelHelper(false,true);
        }
        else if($query->num_rows() === 1){
            return $this->ModelHelper(true,false);
        }   
    }
    public function CheckOTCStockExist($where){
        $this->db->select('*');
        $this->db->from('mss_inventory');
        $this->db->where('service_id',$where['service_id']);
        $query = $this->db->get();
        if($query->num_rows() === 0){
            return $this->ModelHelper(false,true);
        }
        else if($query->num_rows() === 1){
            return $this->ModelHelper(true,false);
        }   
    }
    public function UpdateRawMaterialStock($data){
        

        $stock = $data['rm_stock'];
        $this->db->set('rm_stock','rm_stock + '.(int)$stock.'',FALSE);
        // $this->db->set('rm_expiry_date',$data['rm_expiry_date']);
        // $this->db->set('rm_entry_date',$data['rm_entry_date']);
        
        $this->db->where('rmc_id', $data['rmc_id']); 
        
        $this->db->update('mss_raw_material_stock');
       

        if($this->db->affected_rows() === 1){
            return $this->ModelHelper(true,false);    
        }
        elseif($this->db->affected_rows() === 0){
            return $this->ModelHelper(true,false,"No row updated!");   
        }
        else{
            return $this->ModelHelper(false,true,"Some DB Error!");
        }  

    }

    public function UpdateOTCStock($data){

        $stock = $data['otc_sku'];
        $this->db->set('otc_sku','otc_sku + '.(int)$stock.'',FALSE);
        // $this->db->set('otc_expiry_date',$data['otc_expiry_date']);
        // $this->db->set('otc_entry_date',$data['otc_entry_date']);
        
        $this->db->where('otc_service_id', $data['otc_service_id']); 
        
        $this->db->update('mss_otc_stock');
       
        if($this->db->affected_rows() === 1){
            return $this->ModelHelper(true,false);    
        }
        elseif($this->db->affected_rows() === 0){
            return $this->ModelHelper(true,false,"No row updated!");   
        }
        else{
            return $this->ModelHelper(false,true,"Some DB Error!");
        }  

    }

    public function VerifyCustomer($where){
        $this->db->select('*');
        $this->db->from('mss_customers');
        $this->db->where('customer_id',$where['customer_id']);
        // $this->db->where('customer_business_outlet_id',$where['business_outlet_id']);
        // $this->db->where('customer_business_admin_id',$where['business_admin_id']);
        $this->db->where('customer_master_admin_id',$where['master_admin_id']);
        $query = $this->db->get();

        if ($query->num_rows() == 1){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'You are not allowed to bill the another customer which is not under you. Please do not change url!');
        }
    }

    public function VerifyOfflineCustomer($where){
        $this->db->select('*');
        $this->db->from('mss_customers');
        $this->db->where('customer_id',$where['customer_id']);
        // $this->db->where('customer_business_outlet_id',$where['business_outlet_id']);
        // $this->db->where('customer_business_admin_id',$where['business_admin_id']);
        //$this->db->where('customer_master_admin_id',$where['master_admin_id']);
        $query = $this->db->get();

        if ($query->num_rows() == 1){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'You are not allowed to bill the another customer which is not under you. Please do not change url!');
        }
    }
    
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
    public function BillingTransaction($data,$outlet_id,$admin_id){	
        /*
            1. Insert data in mss_transactions
            2. Insert data in mss_transaction_services
                -> During the processing of the cart data also check if the service is taken through package
                    -> if Yes
                        -> Then Update mss_customer_profile_table
                        -> Insert in to mss_package_redemption_history
                    -> if no
                        -> Proceed as usual
            3. Insert data in mss_transaction_settlements
            4. Update the pending amounts for the customers if any
            5. Last but not least if composition is available then update the stock for the services taken.
        */
		// $this->PrintArray($_POST);
		//exit;
				if($data['cashback']>0)
                {
                    $data_cashback = array(
                        'business_outlet_id' => $outlet_id,
                        'net_amount' => $data['txn_data']['txn_value']
                    );
                    $cashback = $this->CheckRule($data_cashback,'mss_loyalty_rules','business_outlet_id');
					if($cashback['success'] == 'true')
					{
						$cashback = $cashback['res_arr'];
					}
					else
					{
						$cashback = ['res_arr'=>''];
						$cashback = $cashback['res_arr'];
					}
				}
                //jitesh ends code
        		//end of calculate points
        $this->db->trans_start();

        $outlet_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$outlet_id)->get()->row_array();
			
          $data['txn_data']['txn_unique_serial_id'] = strval("A".strval(100+$admin_id) . "O" . strval($outlet_id) . "-" . strval($outlet_counter['business_outlet_bill_counter']));
        
        
        //1.unset sender id and api key from array;
		unset($data['txn_data']['sender_id'],$data['txn_data']['api_key']);
				// $data['txn_data']+=['txn_loyalty_points'=>$_POST['cashback']];
				if(!empty($cashback))
				{
					if($cashback['rule_type'] == 'Offers Single Rule' || $cashback['rule_type'] == 'Offers Multiple Rule' || $cashback['rule_type'] == 'Offers LTV Rule')
					{
						$data['txn_data']+=['txn_loyalty_points'=>$cashback['points_generated']];
						$data['txn_data']+=['txn_loyalty_points_expiry'=>date('Y-m-d', strtotime("+".$cashback['points_validity'], strtotime(date("Y-m-d"))))];
					}
					else if ($cashback['rule_type'] == 'Cashback Single Rule' || $cashback['rule_type'] == 'Cashback Multiple Rule' || $cashback['rule_type'] == 'Cashback LTV Rule')
					{
						$data['txn_data']+=['txn_loyalty_cashback'=>$cashback['cashback_generated']];
						$data['txn_data']+=['txn_loyalty_cashback_expiry'=>date('Y-m-d', strtotime("+".$cashback['cashback_validity'], strtotime(date("Y-m-d"))))];   
					}
							//
					
				}
				else
				{

				}
				
				$result_1 = $this->Insert($data['txn_data'],'mss_transactions');
				
					$query = "UPDATE mss_business_outlets SET business_outlet_bill_counter = business_outlet_bill_counter + 1 WHERE business_outlet_id = ".$outlet_id."";
					
					$this->db->query($query);
				// 	$this->PrintArray("affected rows ".$this->db->affected_rows());
					
				//Update CustomerCoupon table for used coupon
				$count_discount=0;
				foreach($data['cart_data'] as $s){
					if($s['service_id']==1){
						$count_discount++;						
					}
				}
				if($count_discount==1){
					$query = "UPDATE mss_customer_coupan SET redemption_date = CURRENT_DATE WHERE mss_customer_coupan.coupan_id = ".$s['coupon_id']." ";
					$this->db->query($query);
				}elseif($count_discount>1){
					return $this->ModelHelper(false,true,'Discount Applied more than once');
					die; 
				}
				
                //2.
                for($i=0;$i<count($data['cart_data']);$i++){
                    $services_data = array(
        					'txn_service_service_id' => $data['cart_data'][$i]['service_id'],
							'txn_service_expert_id'  => $data['cart_data'][$i]['service_expert_id'],
							'txn_service_txn_id'     => $result_1['res_arr']['insert_id'],
							'txn_service_quantity'   => $data['cart_data'][$i]['service_quantity'],
							'txn_service_discount_percentage' => $data['cart_data'][$i]['service_discount_percentage'],
							'txn_service_discount_absolute'   => $data['cart_data'][$i]['service_discount_absolute'],
							'txn_service_remarks'		=> $data['cart_data'][$i]['service_remarks'],
							'txn_add_on_amount'		=> $data['cart_data'][$i]['service_add_on_price']
						);

						//for percentage discount
						if($data['cart_data'][$i]['service_discount_percentage'] > 0){
							$services_data+=['txn_service_discounted_price' => (int)($data['cart_data'][$i]['service_total_value']-($data['cart_data'][$i]['service_total_value']*$data['cart_data'][$i]['service_discount_percentage'])/100)];
						}else if($data['cart_data'][$i]['service_discount_absolute'] > 0){
							$services_data+=['txn_service_discounted_price' => ($data['cart_data'][$i]['service_total_value']-$data['cart_data'][$i]['service_discount_absolute'])];
						}else{
							$services_data+=['txn_service_discounted_price' => $data['cart_data'][$i]['service_total_value']];
						}
						
					$result_2 = $this->Insert($services_data,'mss_transaction_services');

						if((int)$data['cart_data'][$i]['customer_package_profile_id'] != -999){
							if((int)$data['cart_data'][$i]['customer_package_profile_id'] != -9999)
							{
								$customer_package_profile_id = $data['cart_data'][$i]['customer_package_profile_id'];
								$where=array('customer_package_profile_id'=> $customer_package_profile_id);
								$total_count=$this->MultiWhereSelect('mss_customer_package_profile',$where);
								$total_count=$total_count['res_arr'][0]['service_count'];
								if($total_count >= $data['cart_data'][$i]['service_quantity']){
									$update_query = "UPDATE mss_customer_package_profile SET service_count = service_count - ".(int)$data['cart_data'][$i]['service_quantity']." WHERE customer_package_profile_id = ".$customer_package_profile_id." ";
									$this->db->query($update_query);
								}else{
									return $this->ModelHelper(false,true,"You can't redeem more than the available services for redemption. Please select lesser count of Services");
									die;
								}
								$package_redemption_data = array(
									'customer_package_profile_id' => $data['cart_data'][$i]['customer_package_profile_id'],
									'redemption_date' => date('Y-m-d'),
									'qty' => $data['cart_data'][$i]['service_quantity']
								);

					$insert_redemption = $this->Insert($package_redemption_data,'mss_package_redemption_history');
            	}
			}
                
        }

		
        //3.
        $settlement_data = array(
            'txn_settlement_txn_id' => $result_1['res_arr']['insert_id'],
            'txn_settlement_way' => $data['txn_settlement']['txn_settlement_way'],
            'txn_settlement_payment_mode' => $data['txn_settlement']['txn_settlement_payment_mode'],
            'txn_settlement_amount_received' =>$data['txn_settlement']['txn_settlement_amount_received'],
            'txn_settlement_balance_paid' =>$data['txn_settlement']['txn_settlement_balance_paid']
        );
		
        $result_3 = $this->Insert($settlement_data,'mss_transaction_settlements');

       
        //4.
        $query = "UPDATE mss_customers SET customer_pending_amount = customer_pending_amount + ".(int)$data['customer_pending_data']['pending_amount']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
        
        $this->db->query($query);

        if($data['txn_settlement']['txn_settlement_payment_mode'] == 'Virtual_Wallet' && $data['txn_settlement']['txn_settlement_way'] == 'Full Payment'){
			// Update the customer wallet as well
			$query = "UPDATE mss_customers SET customer_virtual_wallet = customer_virtual_wallet - ".(int)$data['txn_settlement']['txn_settlement_amount_received']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
				$this->db->query($query);
			}				

				//5 loyalty wallet payment
				//jitesh
				
				if(!empty($cashback))
				{
					if($cashback['rule_type'] == 'Offers Single Rule' || $cashback['rule_type'] == 'Offers Multiple Rule' || $cashback['rule_type'] == 'Offers LTV Rule')
					{
							$update_cashback="UPDATE mss_customers SET customer_rewards = customer_rewards + ".$cashback['points_generated']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
							$this->db->query($update_cashback);
					}
					else if ($cashback['rule_type'] == 'Cashback Single Rule' || $cashback['rule_type'] == 'Cashback Multiple Rule' || $cashback['rule_type'] == 'Cashback LTV Rule')
					{

							$update_cashback="UPDATE mss_customers SET customer_cashback = customer_cashback + ".$cashback['cashback_generated']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
							$this->db->query($update_cashback);
					}
				}
				else
				{
					$update_cashback = $update_cashback="UPDATE mss_customers SET customer_cashback = customer_cashback + 0 WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
					$this->db->query($update_cashback);
				}
				
				// $update_cashback="UPDATE mss_customers SET customer_rewards = customer_rewards + ".$cashback[0]." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
				// $this->db->query($update_cashback);
				//
				// $update_cashback="UPDATE mss_customers SET customer_rewards = customer_rewards + ".$_POST['cashback']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
				// $this->db->query($update_cashback);
				
				if($data['txn_settlement']['txn_settlement_payment_mode'] == 'loyalty_wallet' && $data['txn_settlement']['txn_settlement_way'] == 'Full Payment'){
						//Update the customer loyalty wallet as well
						$query = "UPDATE mss_customers SET customer_rewards = customer_rewards - ".$data['txn_settlement']['txn_settlement_amount_received']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
				
						$this->db->query($query);            
					}
				//loyalty split payment
				if($data['txn_settlement']['txn_settlement_way'] == 'Split Payment'){
					$sp_array=array(json_decode($_POST['txn_settlement']['txn_settlement_payment_mode'],true));
						$sp_array=$sp_array[0];
					foreach($sp_array as $k=>$v){
						if($v['payment_type']=="loyalty_wallet"){
							$split_loyalty_payment= $v['payment_type'];
							$payment_form_rewards= $v['amount_received'];
						}
						//
						if($v['payment_type']=="Virtual_Wallet"){
							$payment_type= $v['payment_type'];
							$payment_amount= $v['amount_received'];
						}
					}
					if(isset($split_loyalty_payment)){
						if($split_loyalty_payment == 'loyalty_wallet' && $data['txn_settlement']['txn_settlement_way'] == 'Split Payment'){
							//Update the customer loyalty wallet as well
							$query = "UPDATE mss_customers SET customer_rewards = customer_rewards - ".(int)$payment_form_rewards." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";			
							$this->db->query($query);   
						}   
					} 
					if(isset($payment_type)){
						if($payment_type == 'Virtual_Wallet' && $data['txn_settlement']['txn_settlement_way'] == 'Split Payment'){
							//Update the customer Virtual wallet as well
							$query = "UPDATE mss_customers SET customer_virtual_wallet = customer_virtual_wallet - ".$payment_amount." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";        
							$this->db->query($query);  
						}   
					}      
				}
		
		//
        // if($data['txn_settlement']['txn_settlement_payment_mode'] == 'Virtual_Wallet' && $data['txn_settlement']['txn_settlement_way'] == 'Split Payment'){
        //     //Update the customer wallet as well
        //     $query = "UPDATE mss_customers SET customer_virtual_wallet = customer_virtual_wallet - ".(int)$data['txn_settlement']['txn_settlement_amount_received']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
        
        //     $this->db->query($query);            
        // }


        //6.
            /*for each service item process:
                1.compostion should exist
                2.if exists then update raw material stock
                3.if not then do nothing
                4.if it is otc item then check whether stock exists
                5.if exists then reduce it by the no. of quantity taken
                6.if not then do nothing
            */

        for($i=0;$i<count($data['cart_data']);$i++){
            $service_data = array(
                 'txn_service_service_id' => $data['cart_data'][$i]['service_id'],
                 'txn_service_quantity'   => $data['cart_data'][$i]['service_quantity']
            );
            
            $this->UpdateStock($service_data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
          return $this->ModelHelper(false,true,'Transaction cannot be processed!');
		}
 
        //array('txn_id' => $result_1['res_arr']['insert_id']
				// return $this->ModelHelper(true,false);
				return $this->ModelHelper(true,false,'',$result_1);
    }


    public function UpdateStock($service_data){
        $service_id = $service_data['txn_service_service_id'];
        $quantity = $service_data['txn_service_quantity'];

        $temp_row = $this->DetailsById($service_id,'mss_services','service_id');
        $service_details = $temp_row['res_arr'];

        if($service_details['service_type'] == 'service'){
            //Check whether service composition exists
            $where = array('service_id' => $service_id);
            $CompositionExists = $this->CheckCompositionExists($where);
            if($CompositionExists['success'] == 'true'){
                //Update the stock by the quantity
                $service_composition = $this->GetCompostion($where);

                //Again Check for each composition item whether its stock exists
                //if exists then reduce it by the consumption quantity
                foreach ($service_composition as $composition) {
                    $result = $this->CheckRmStockExists(array('rmc_id' => $composition['rmc_id']));  
                    if($result['success'] == 'true'){
                        //Subtract the composition consumption quantity from stock
                        $temp = array(
                            'rmc_id' =>$composition['rmc_id'],
                            'consumption_quantity' => (int)$composition['consumption_quantity'] * (int)$quantity
                        );
                        $this->UpdateStockFromComposition($temp);
                    }
                }
            }
        }
       elseif ($service_details['service_type'] == 'otc') {
            $where = array('service_id' => $service_id);
            $OTCexists = $this->CheckOTCStockExists($where);
            if($OTCexists['success'] == 'true'){
                //Subtract the consumption quantity from stock
                $temp = array(
                    'otc_service_id' => $service_id,
                    'consumption_quantity' =>(int)$quantity
                );
                $this->UpdateStockFromOTC($temp);
            }else{                
                $temp = array(
                    'stock_service_id' => $service_id,
					'total_stock'	=>0,
					'stock_outlet_id'=>$this->session->userdata['logged_in']['business_outlet_id'],
					'updated_on'=>date('Y-m-d')
                );
				$this->Insert($temp,'inventory_stock');
				$temp1 = array(
                    'otc_service_id' => $service_id,
                    'consumption_quantity' =>(int)$quantity
                );
                $this->UpdateStockFromOTC($temp1);
            }
        }






    }

    public function GetCurrentOTCStock($where){
       $sql = "SELECT * FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C,mss_otc_stock AS D WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND C.service_id = D.otc_service_id AND A.category_business_admin_id = ".$this->db->escape($where['category_business_admin_id'])." AND C.service_is_active = 1 AND A.category_business_outlet_id = ".$this->db->escape($where['category_business_outlet_id'])." AND C.service_type = 'otc' GROUP BY D.otc_entry_date,C.service_name DESC";
        
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    //Total OTC STOCK
  
	public function Total_Otc_Stock($where){
		$sql = "SELECT count(D.otc_sku) AS 'count' FROM mss_categories AS A,mss_sub_categories AS B,mss_services AS C,mss_otc_stock AS D WHERE A.category_id = B.sub_category_category_id AND B.sub_category_id = C.service_sub_category_id AND C.service_id = D.otc_service_id AND A.category_business_admin_id = ".$this->db->escape($where['business_admin_id'])." AND C.service_is_active = 1 AND A.category_business_outlet_id = ".$this->db->escape($where['business_outlet_id'])." AND C.service_type = 'otc' ";
		
		$query = $this->db->query($sql);
		
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
	 }
    public function GetCurrentRMStock($where){
        $sql = "SELECT 
			* 
			FROM 
				mss_raw_material_categories,
				mss_raw_material_stock 
			WHERE 
			mss_raw_material_stock.rmc_id =mss_raw_material_categories.raw_material_category_id
			AND 
				mss_raw_material_categories.raw_material_business_outlet_id = ".$this->db->escape($where['raw_material_business_outlet_id'])." AND mss_raw_material_categories.raw_material_business_admin_id = ".$this->db->escape($where['raw_material_business_admin_id'])." AND mss_raw_material_categories.raw_material_is_active = 1";
		$query = $this->db->query($sql);        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    private function GetCompostion($where){
        $sql = "SELECT * FROM mss_raw_composition WHERE service_id = ".$where['service_id']."";
        $query = $this->db->query($sql);
        
        if($query){
            return $query->result_array();
        }
        else{
            return false;   
        }
    }

    private function UpdateStockFromComposition($data){
         $query = "UPDATE mss_raw_material_stock SET rm_stock = rm_stock  - ".(int)$data['consumption_quantity']." WHERE rmc_id = ".$data['rmc_id']."";
        $this->db->query($query);  
    }

    private function UpdateStockFromOTC($data){
		$query1 = "UPDATE mss_inventory SET sku_count = sku_count  - ".(int)$data['consumption_quantity']." WHERE service_id = ".$data['otc_service_id']."";
		//$query1="UPDATE  inventory_stock SET total_stock=total_stock - ".$data['consumption_quantity']." WHERE stock_service_id=".$data['otc_service_id']." ";
        $this->db->query($query1); 
    }

    public function GetAllExpenses($where){
        $sql = "SELECT * FROM mss_expense_types,mss_expenses WHERE mss_expense_types.expense_type_id = mss_expenses.expense_type_id AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['expense_type_business_admin_id'])." AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['expense_type_business_outlet_id'])."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetTopExpensesSummary($where){
        
        $sql = "SELECT expense_date,SUM(amount) AS outflow FROM mss_expenses,mss_expense_types WHERE mss_expense_types.expense_type_id = mss_expenses.expense_type_id AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['expense_type_business_admin_id'])." AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['expense_type_business_outlet_id'])." GROUP BY expense_date ORDER BY expense_date DESC LIMIT 10";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetExpensesSummaryRange($where){
        $sql = "SELECT expense_date,SUM(amount) AS outflow FROM mss_expenses,mss_expense_types WHERE mss_expense_types.expense_type_id = mss_expenses.expense_type_id AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['expense_type_business_admin_id'])." AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['expense_type_business_outlet_id'])." GROUP BY expense_date HAVING expense_date BETWEEN ".$this->db->escape($where['from_date'])." AND ".$this->db->escape($where['to_date'])." ORDER BY expense_date DESC ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetTopExpensesSummaryRange($where){
        $sql = "SELECT 
					mss_expenses.item_name,
					mss_expenses.expense_date,
					SUM(mss_expenses.amount)'outflow'
				FROM
					mss_expenses,
					mss_expense_types 
				WHERE 
					mss_expense_types.expense_type_id = mss_expenses.expense_type_id AND mss_expense_types.expense_type_business_admin_id = ".$this->db->escape($where['expense_type_business_admin_id'])." AND mss_expense_types.expense_type_business_outlet_id = ".$this->db->escape($where['expense_type_business_outlet_id'])." 
				GROUP BY 
					item_name 
				HAVING expense_date BETWEEN ".$this->db->escape($where['date_from'])." AND ".$this->db->escape($where['date_to'])." ORDER BY outflow DESC LIMIT 5";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetPackageDetails($salon_package_id){
        $sql = "SELECT * FROM mss_salon_packages,mss_salon_package_data,mss_services WHERE mss_salon_packages.salon_package_id = mss_salon_package_data.salon_package_id AND mss_salon_package_data.service_id = mss_services.service_id AND mss_salon_packages.salon_package_id = ".$this->db->escape($salon_package_id)."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

     public function BillingPackageTransaction($data,$outlet_id,$admin_id){
		//  $this->PrintArray($data);
		//  exit;

        /*
            1. Insert data in mss_package_transactions
            2. Insert data in mss_transaction_package_details
            3. Insert data in mss_package_transaction_settlements
            4. Update the pending amounts for the customers if any 
            
            if PackageType = Wallet
                5. Update the virtual wallet amount with the expiry date.

            else if PackageType == Services OR PackageType = Discount
                5. Create Customer Profile for the package
        
        Array  
        (  
            [txn_data] => Array
                (
                    [package_txn_customer_id] => 12
                    [package_txn_discount] => 110
                    [package_txn_value] => 4890
                )

            [txn_settlement] => Array
                (
                    [settlement_way] => Full Payment
                    [payment_mode] => Cash
                    [amount_received] => 4800
                    [balance_paid_back] => 0
                )

            [customer_pending_data] => Array
                (
                    [customer_id] => 12
                    [pending_amount] => 90
                )

            [cart_data] => Array
                (
                    [package_name] => Independence Speicial
                    [customer_name] => Ashok Kumar Patel
                    [package_discount_absolute] => 110
                    [package_price_inr] => 5000
                    [package_validity] => 2
                    [package_final_value] => 4890
                    [package_type] => Wallet
                    [customer_id] => 12
                    [salon_package_id] => 3
                )

            )
        */
		$this->db->trans_start();
		
		$outlet_counter = $this->db->select('*')->from('mss_business_outlets')->where('business_outlet_id',$outlet_id)->get()->row_array();
			
        $data['txn_data']['package_txn_unique_serial_id'] = strval("A".strval(100+$admin_id) . "O" . strval($outlet_id) . "-" . strval($outlet_counter['business_outlet_bill_counter']));
        
        
        //1.unset sender id and api key from array;
		unset($data['txn_data']['sender_id'],$data['txn_data']['api_key']);
		$data['txn_data']+=['package_txn_pending_amount' => $data['customer_pending_data']['pending_amount']];
		
	    $result_1 = $this->Insert($data['txn_data'],'mss_package_transactions');

        $query = "UPDATE mss_business_outlets SET business_outlet_bill_counter = business_outlet_bill_counter + 1 WHERE business_outlet_id = ".$outlet_id."";
        $this->db->query($query);
		//1.
		// unset($data['txn_data']['sender_id'],$data['txn_data']['api_key']);
        // $result_1 = $this->Insert($data['txn_data'],'mss_package_transactions');

        //2.
        $packages_data = array(
			'package_txn_id'       => $result_1['res_arr']['insert_id'],
			'salon_package_id'     => $data['cart_data']['salon_package_id'],
			'txn_package_price'    => $data['cart_data']['package_final_value']
        );
    
        $result_2 = $this->Insert($packages_data,'mss_transaction_package_details');
        
        //3.
        $settlement_data = array(
            'package_txn_id'     => $result_1['res_arr']['insert_id'],
            'settlement_way'     => $data['txn_settlement']['settlement_way'],
            'payment_mode'       => $data['txn_settlement']['payment_mode'],
            'amount_received'    => $data['txn_settlement']['amount_received'],
            'balance_paid_back'  => $data['txn_settlement']['balance_paid_back']
        );

        $result_3 = $this->Insert($settlement_data,'mss_package_transaction_settlements');

		// $this->PrintArray($data['customer_pending_data']['pending_amount']);
        //4.
		$query = "UPDATE mss_customers SET customer_pending_amount = customer_pending_amount + ".(int)$data['customer_pending_data']['pending_amount']." WHERE customer_id = ".$data['customer_pending_data']['customer_id']."";
		// $this->PrintArray($query);
        $this->db->query($query);

        //5.         
        if($data['cart_data']['package_type'] == 'Wallet'){
            $package_details = $this->DetailsById($data['cart_data']['salon_package_id'],'mss_salon_packages','salon_package_id');
            $customer_wallet = $this->GetWalletInfo($data['cart_data']['customer_id']);

            $expiry_date = $customer_wallet['res_arr']['customer_wallet_expiry_date'];
            $current_balance = $customer_wallet['res_arr']['customer_virtual_wallet'];
            if($expiry_date == NULL){
                $expiry_date = date("Y-m-d");
                $expiry_date = strtotime(date("Y-m-d", strtotime($expiry_date)) . " + ".$data['cart_data']['package_validity']." month");

                $customer_data = array(
                    'customer_id' => $data['cart_data']['customer_id'],
                    'customer_virtual_wallet' => (int)$package_details['res_arr']['virtual_wallet_money'],
                    'customer_wallet_expiry_date' => date("Y-m-d",$expiry_date)
                );           
            }
            else{
                if($expiry_date >= date('Y-m-d')){
                    $new_expiry_date = date('Y-m-d');
                    $new_expiry_date = strtotime(date("Y-m-d",strtotime($new_expiry_date)) . " + ".$data['cart_data']['package_validity']." month");
                    if($expiry_date < $new_expiry_date){
                        $expiry_date = $new_expiry_date;
                    }

                    $current_balance = $current_balance + (int)$package_details['res_arr']['virtual_wallet_money'];
                   
                    $customer_data = array(
                        'customer_id' => $data['cart_data']['customer_id'],
                        'customer_virtual_wallet' => $current_balance,
                        'customer_wallet_expiry_date' => date("Y-m-d",$expiry_date)
                    );
                }
                else{
                    //package already expired
                    $expiry_date = date("Y-m-d");
                    $expiry_date = strtotime(date("Y-m-d", strtotime($expiry_date)) . " + ".$data['cart_data']['package_validity']." month");

                    $customer_data = array(
                        'customer_id' => $data['cart_data']['customer_id'],
                        'customer_virtual_wallet' => (int)$package_details['res_arr']['virtual_wallet_money'],
                        'customer_wallet_expiry_date' => date("Y-m-d",$expiry_date)
                    );
                }
            }
            $this->db->where('customer_id', $customer_data['customer_id']);
            $this->db->update('mss_customers', $customer_data);

            $customer_packages_data = array(
                'customer_id' => $data['cart_data']['customer_id'],
                'salon_package_id' => $data['cart_data']['salon_package_id'],
                'salon_package_type' => $data['cart_data']['package_type'],
                'package_expiry_date' => date("Y-m-d",$expiry_date)
            );

            $result_4 = $this->Insert($customer_packages_data,'mss_customer_packages'); 
        }
        elseif($data['cart_data']['package_type'] == 'Services' || $data['cart_data']['package_type'] == 'Discount' || $data['cart_data']['package_type']=='special_membership'){ 
            // $this->PrintArray($_POST);           
            $expiry_date = date('Y-m-d');
            $expiry_date = strtotime(date("Y-m-d", strtotime($expiry_date)) . " + ".$data['cart_data']['package_validity']." month");
            $customer_packages_data = array(
                'customer_id' => $data['cart_data']['customer_id'],
                'salon_package_id' => $data['cart_data']['salon_package_id'],
                'salon_package_type' => $data['cart_data']['package_type'],
                'package_expiry_date' => date("Y-m-d",$expiry_date)
            );

            $result_4 = $this->Insert($customer_packages_data,'mss_customer_packages');
            //After this demand is customer profile should be created for the redemption
            
            //(i) Get the data from the salon_package_id from table `mss_salon_package_data
            $result_5 = $this->GetPackageDetails($data['cart_data']['salon_package_id']);
            $result_5 = $result_5['res_arr'];
						// $this->PrintArray($result_5);
            // (ii) Now Create the profile
            foreach ($result_5 as $service) {
                $customer_package_profile = array(
									'customer_package_id' => $result_4['res_arr']['insert_id'],
									'service_id'          => $service['service_id'],
									'service_discount'    => $service['discount_percentage'],
									'service_monthly_discount'=>$service['service_monthly_discount'],
									'birthday_discount'	=> $service['birthday_discount'],
									'anni_discount'	=>	$service['anni_discount'],
                  'service_count'       => $service['service_count']
                );

                $result_6 = $this->Insert($customer_package_profile,'mss_customer_package_profile');    
			}
			//
			function generateRandomString($length = 6) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				return $randomString;
			}
			if($data['cart_data']['package_type']=='special_membership'){
                $customer_coupan_profile = array(
                'customer_id' 		=> $data['cart_data']['customer_id'],
                'salon_package_id'  =>$data['cart_data']['salon_package_id'],
                'customer_package_id'=>$result_4['res_arr']['insert_id'],
                'coupan_code'		=>generateRandomString(),
                'coupan_status'     =>1,
                'coupan_expiry_date' => date("Y-m-d",$expiry_date),
                'service_monthly_discount' =>$result_5[0]['service_monthly_discount'],
                'birthday_discount'=>0,
                'anni_discount'=>0
                );
                $customer_coupan_profile2 = array(
                'customer_id' 		=> $data['cart_data']['customer_id'],
                'salon_package_id'  =>$data['cart_data']['salon_package_id'],
                'customer_package_id'=>$result_4['res_arr']['insert_id'],
                'coupan_code'		=>generateRandomString(),
                'coupan_status'     =>1,
                'coupan_expiry_date' => date("Y-m-d",$expiry_date),
                'service_monthly_discount' =>0,
                'birthday_discount'=>$result_5[0]['birthday_discount'],
                'anni_discount'=>0
                );
                $customer_coupan_profile3 = array(
                'customer_id' 		=> $data['cart_data']['customer_id'],
                'salon_package_id'  =>$data['cart_data']['salon_package_id'],
                'customer_package_id'=>$result_4['res_arr']['insert_id'],
                'coupan_code'		=>generateRandomString(),
                'coupan_status'     =>1,
                'coupan_expiry_date' => date("Y-m-d",$expiry_date),
                'service_monthly_discount' =>0,
                'birthday_discount'=>0,
                'anni_discount'=>$result_5[0]['anni_discount']
                );
                $result_7 = $this->Insert($customer_coupan_profile,'mss_customer_coupan');
                $result_8 = $this->Insert($customer_coupan_profile2,'mss_customer_coupan');
                $result_9 = $this->Insert($customer_coupan_profile3,'mss_customer_coupan');    
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return $this->ModelHelper(false,true,'Transaction cannot be processed!');
        }

        return $this->ModelHelper(true,false);
    }

    public function GetWalletInfo($customer_id){
        $this->db->select('customer_virtual_wallet,customer_wallet_expiry_date,customer_rewards,customer_cashback');
        $this->db->from('mss_customers');
        $this->db->where('customer_id',$customer_id);
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
    
    public function GetCustomerCoupon($customer_id){
				// $sql = "SELECT 
				// *
				// FROM
				// 	mss_customer_coupan
				// WHERE
        //   mss_customer_coupan.redemption_date NOT BETWEEN date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH) AND CURRENT_DATE AND mss_customer_coupan.customer_id = ".$this->db->escape($customer_id)." ";
        $sql = "SELECT 
				*
				FROM
					mss_customer_coupan
				WHERE
					mss_customer_coupan.customer_id = ".$this->db->escape($customer_id)." ";

				//execute the query
				$query = $this->db->query($sql);
        // $this->PrintArray($query->result_array());
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"Duplicate rows found!");
        }   
  }
    
    public function GetDealInfo($data){
        $sql = "SELECT mss_deals_discount.*,
					mss_deals_data.service_id,
					mss_customer_tag_data.customer_id
				FROM
					mss_deals_discount,
					mss_deals_data,
					mss_customer_tag_data
				WHERE
					mss_customer_tag_data.customer_id=".$this->db->escape($data['customer_id'])." AND
					mss_deals_discount.deal_id=mss_deals_data.deal_id AND
					mss_deals_discount.deal_id=mss_customer_tag_data.deal_id AND
					mss_deals_discount.deal_code= ".$this->db->escape($data['coupon_code'])." AND
					mss_deals_discount.deal_business_outlet_id=".$this->db->escape($data['business_outlet_id'])." AND
					mss_deals_discount.deal_business_admin_id=".$this->db->escape($data['business_admin_id'])."";

				//execute the query
				$query = $this->db->query($sql);
        // $this->PrintArray($query->result_array());
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Deal Found!");
        }   
  	}

	public function GetCustomerTransactionDiscount($customer_id){
				$sql = "SELECT 
				*
				FROM
					mss_customer_coupan
				WHERE
					mss_customer_coupan.customer_id = ".$this->db->escape($customer_id)."
						AND mss_customer_coupan.redemption_date < date_add(date_add(LAST_DAY(CURRENT_DATE),interval 1 DAY),interval -1 MONTH)";

				//execute the query
				$query = $this->db->query($sql);

				if ($query->num_rows() == 1){
					return $this->ModelHelper(true,false,'',$query->row_array());
				} 
				else{
					return $this->ModelHelper(false,true,"Some error occured!");
				} 
    }

    
    public function GetCompleteCustomer($customer_id){

        $this->db->select('*');
        $this->db->from('mss_customers');
        $this->db->where('customer_id',$customer_id);
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() == 1){
            $result = array();
            $result = $query->row_array();

            $sql = "SELECT  mss_transactions.txn_value,mss_transactions.txn_discount,date(mss_transactions.txn_datetime) AS BillDate FROM mss_customers,mss_transactions WHERE mss_customers.customer_id = mss_transactions.txn_customer_id AND mss_customers.customer_id = ".$this->db->escape($customer_id)." AND mss_transactions.txn_status=1 ORDER BY mss_transactions.txn_datetime DESC LIMIT 4";            
            
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
						mss_transactions.txn_status=1 AND
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

    public function BoolPackageCustomer($customer_id){
        $sql = "SELECT MAX(mss_customer_packages.package_expiry_date), 
			SUM(mss_customer_package_profile.service_count) AS 'service_count' 
			FROM mss_customer_packages, 
				mss_customer_package_profile 
			WHERE mss_customer_packages.customer_package_id= mss_customer_package_profile.customer_package_id AND 		mss_customer_packages.package_expiry_date > CURRENT_DATE AND 
				mss_customer_packages.customer_id =".$this->db->escape($customer_id)." ";

        //execute the query
        $query = $this->db->query($sql);
        
        if ($query->num_rows() == 1){
           return $this->ModelHelper(true,false,'',$query->row_array());
        } 
        else{
           return $this->ModelHelper(false,true,"Some error occured!");
        } 
    }

    //Appointment Module start

    //This query can be used to fetch appointments for the shop-1 of business admin-1 for the current month

    /*$query = "SELECT
                    mss_appointments.*,
                    mss_customers.*,
                    mss_services.*,
                    mss_employees.employee_id,
                    mss_employees.employee_first_name,
                    mss_employees.employee_last_name
                FROM
                    mss_appointments,
                    mss_appointment_services,
                    mss_services,
                    mss_employees,
                    mss_customers
                WHERE
                    mss_appointments.customer_id = mss_customers.customer_id
                    AND mss_appointments.appointment_id = mss_appointment_services.appointment_id
                    AND mss_appointment_services.expert_id = mss_employees.employee_id
                    AND mss_appointment_services.service_id = mss_services.service_id
                    AND mss_customers.customer_business_admin_id = 1
                    AND mss_customers.customer_business_outlet_id = 1
                    AND mss_employees.employee_business_outlet = 1
                    AND mss_employees.employee_business_admin = 1
                    AND mss_appointments.appointment_status = 1
                    AND mss_appointments.appointment_date BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND LAST_DAY(date(now()))";
    */
    
    public function GetAllAppointments($where){
        $sql = "SELECT
                    mss_appointments.*,
                    mss_customers.*,
                    mss_services.*,
					mss_categories.category_name,
					mss_sub_categories.sub_category_name,
                    mss_employees.employee_id,
                    mss_employees.employee_first_name,
                    mss_employees.employee_last_name
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
					AND mss_appointments.appointment_date >=".$this->db->escape($where['appointment_date'])."
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

    // Checking Appointment Exists
    public function CheckAppointmentExists($where){
        $this->db->select('*');
        $this->db->from('mss_appointments');
        $this->db->where('customer_id',$where['customer_id']);
        $this->db->where('appointment_date',$where['appointment_date']);
        $this->db->where('appointment_start_time',$where['appointment_start_time']);
        $this->db->where('appointment_status',$where['appointment_status']);
        
        $query = $this->db->get();
        if ($query->num_rows() == 0){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'You have already booked an Appoinment on selected date and Time. Please Change date or time !');
        }
    }

    // Checking Appointment Exsiting
    public function CheckExpertAvailable($where){
		$end_time= date('h:i:s',strtotime($where['appointment_start_time'])+3600);
        $sql = "SELECT 
                    * 
                FROM 
                    mss_appointments,
                    mss_appointment_services 
                WHERE 
                    mss_appointments.appointment_id = mss_appointment_services.appointment_id
					AND mss_appointments.appointment_start_time BETWEEN '".strval($where['appointment_start_time'].":00")."' AND '".$end_time."'
                    AND mss_appointment_services.expert_id = ".$this->db->escape($where['expert_id'])."
                    AND mss_appointments.appointment_status = ".$this->db->escape($where['appointment_status'])."
                    AND mss_appointments.appointment_date = '".date($where['appointment_date'])."'";
    // $this->PrintArray($sql);
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0){
            return $this->ModelHelper(true,false,'Expert is not available. Please Select another Expert.');
        }
        else{
            return $this->ModelHelper(false,true);
        }
    }

    public function AddAppointmentModel($data,$services,$expert_id){
        $result_1 = $this->Insert($data,'mss_appointments');
        
			
        for($i=0;$i<count($services);$i++){
            $temp = array(
                'appointment_id' => $result_1['res_arr']['insert_id'],
                'expert_id'      => $expert_id,
                'service_id'     => $services[$i]
            );

            $result_2 = $this->Insert($temp,'mss_appointment_services');
            $temp = array();
        }

        return $this->ModelHelper(true,false);
    }

	//Update appointment
	public function UpdateAppointmentModel($data){
     	$sql="UPDATE 
			mss_appointments,mss_appointment_services 
			SET
			mss_appointments.appointment_date=".$this->db->escape($data['appointment_date']).", mss_appointments.appointment_start_time=".$this->db->escape($data['appointment_start_time']).",
			mss_appointments.appointment_end_time=".$this->db->escape($data['appointment_end_time']).",
			mss_appointment_services.service_id=".$data['service_id'].",
			mss_appointment_services.expert_id=".$data['expert_id']."
			WHERE
			mss_appointments.appointment_id=".$data['appointment_id']." 
			AND
			mss_appointment_services.appointment_id=".$data['appointment_id']." ";  
        
        
		$query = $this->db->query($sql);    
		if($query){
			return $this->ModelHelper(true,false,'Appointnment updated successfully.');
		}
		else{
		return $this->ModelHelper(false,true,"DB error!");   
		}   
        
  	}
	
    //
    public function GetAppointment($ap_id){
        $sql = "SELECT 
                    mss_appointments.*,
                    mss_appointment_services.*,
                    mss_services.*,
                    mss_customers.customer_id
                FROM
                    mss_appointments,
                    mss_appointment_services,
                    mss_services,
                    mss_customers
                WHERE
                    mss_appointments.appointment_id = mss_appointment_services.appointment_id
                    AND mss_appointment_services.service_id = mss_services.service_id
                    AND mss_appointments.customer_id = mss_customers.customer_id
                    AND mss_appointments.appointment_id = ".$this->db->escape($ap_id)."";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->row_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    //AppointmentServices
	public function AppointmentServices($business_admin_id,$business_outlet_id){
		$sql = "SELECT 
						mss_services.service_name
					FROM 
						mss_services,
						mss_sub_categories,
						mss_categories
					WHERE
						mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
						AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
						AND mss_categories.category_business_admin_id = ".$this->db->escape($business_admin_id)."
						AND mss_categories.category_business_outlet_id = ".$this->db->escape($business_outlet_id)."
						AND mss_services.service_is_active = TRUE";
			
			$query = $this->db->query($sql);
			
			if($query){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			}  

	}
	//Get Tommorows Appointment
	public function GetTommorowsAppointment(){
		$sql="SELECT 
			mss_appointments.appointment_date, 
			mss_appointments.appointment_start_time, 
			mss_customers.customer_name,
			mss_customers.customer_mobile,
			mss_business_outlets.business_outlet_name 
		FROM mss_appointments,
			mss_customers,
			mss_business_outlets 
		WHERE 
			mss_appointments.appointment_date= ".$this->db->escape(date("Y-m-d", strtotime("+1 day")))." 
		AND 
			mss_appointments.appointment_status=1
		AND
			mss_customers.customer_id=mss_appointments.customer_id 
		AND
			mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id;		
		";
		$query = $this->db->query($sql);
			
			if($query){
				return $query->result_array();
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			}  
	}
	//Appointment Module end
	public function ActivePackage($business_admin_id,$business_outlet_id){
		$sql = "SELECT DISTINCT
		mss_salon_packages.salon_package_id,
		mss_salon_packages.salon_package_name,
		mss_salon_packages.salon_package_validity,
		mss_salon_packages.salon_package_type,
		mss_salon_packages.virtual_wallet_money,
		mss_salon_packages.salon_package_date,
		mss_salon_package_data.discount_percentage,
		mss_salon_package_data.service_count,
		mss_salon_packages.salon_package_upfront_amt
		FROM 
		mss_salon_packages,mss_salon_package_data
		WHERE 	
		mss_salon_packages.is_active=1 			
		AND 
			mss_salon_packages.salon_package_id=mss_salon_package_data.salon_package_id
		AND 
			mss_salon_packages.business_admin_id=".$this->db->escape($business_admin_id)." 
		AND
			mss_salon_packages.business_outlet_id=".$this->db->escape($business_outlet_id)." ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	//Package Redemption
	public function GetPackageRedemptionDetails($where){
		$sql = "SELECT 
		mss_customers.customer_name,
		mss_customers.customer_mobile,
		mss_services.service_name,
		mss_package_redemption_history.redemption_date,
		mss_package_redemption_history.qty,
		mss_customer_packages.salon_package_type,
		mss_customer_package_profile.service_count,
		mss_customer_packages.package_expiry_date,
		mss_customer_packages.datetime_of_purchase,
		mss_salon_packages.salon_package_name
		FROM 
		mss_package_redemption_history,
		mss_customer_package_profile,
		mss_customer_packages,
		mss_salon_packages,
		mss_customers,mss_services 
		WHERE mss_package_redemption_history.customer_package_profile_id=mss_customer_package_profile.customer_package_profile_id 
		AND 
		mss_customer_package_profile.customer_package_id=mss_customer_packages.customer_package_id 
		AND 
		mss_customer_package_profile.service_id=mss_services.service_id
		AND
		mss_customer_packages.customer_id=mss_customers.customer_id
		AND
		mss_customer_packages.salon_package_id=mss_salon_packages.salon_package_id 
		AND 
		mss_package_redemption_history.redemption_date > ".$this->db->escape($where['redemption_date'])."
		AND 
		mss_salon_packages.business_admin_id=".$this->db->escape($where['business_admin_id'])."
		AND 
		mss_salon_packages.business_outlet_id=".$this->db->escape($where['business_admin_id'])." ";

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
	}
	//Filered Data
    public function GetRedemptionFilter($data){
		$sql = "SELECT 
		mss_customers.customer_name,
		mss_customers.customer_mobile,
		mss_services.service_name,
		mss_package_redemption_history.redemption_date,
		mss_package_redemption_history.qty,
		mss_customer_package_profile.service_count,
		mss_customer_packages.salon_package_type,
		mss_customer_packages.package_expiry_date,
		mss_customer_packages.datetime_of_purchase,
		mss_salon_packages.salon_package_name
		FROM 
		mss_package_redemption_history,
		mss_customer_package_profile,
		mss_customer_packages,
		mss_salon_packages,
		mss_customers,mss_services 
		WHERE mss_package_redemption_history.customer_package_profile_id=mss_customer_package_profile.customer_package_profile_id 
		AND 
		mss_customer_package_profile.customer_package_id=mss_customer_packages.customer_package_id 
		AND 
		mss_customer_package_profile.service_id=mss_services.service_id
		AND
		mss_customer_packages.customer_id=mss_customers.customer_id
		AND
		mss_customer_packages.salon_package_id=mss_salon_packages.salon_package_id
		AND 
		mss_salon_packages.business_admin_id=".$this->db->escape($data['business_admin_id'])."
		AND 
		mss_salon_packages.business_outlet_id=".$this->db->escape($data['business_admin_id'])."
		AND 
		date(mss_package_redemption_history.redemption_date) BETWEEN ".$this->db->escape($data['from_date'])." 
		AND 
		".$this->db->escape($data['to_date'])." ";

		$query = $this->db->query($sql);
		
		if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
	}

    //spackage
	public function GetCustomerPackages($where)
  {  
      $sql = "SELECT 
                  *
              FROM 
                  mss_customer_packages,
                  mss_customer_package_profile,
                  mss_services,
                  mss_salon_packages
              WHERE 
                  mss_customer_packages.customer_package_id = mss_customer_package_profile.customer_package_id 
                  AND mss_customer_package_profile.service_id = mss_services.service_id
                  AND mss_customer_packages.salon_package_id = mss_salon_packages.salon_package_id
                  AND mss_salon_packages.salon_package_type = 'special_membership'
                  AND mss_customer_packages.customer_id = ".$this->db->escape($where)." 
                  AND mss_customer_package_profile.service_count > 0
                  AND mss_customer_packages.package_expiry_date > DATE(NOW())";
                      
            //execute the query
        $query = $this->db->query($sql);
            if($query->num_rows() > 0){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            }
  }
  public function SpecialLoyaltyPoints($data,$outlet_id,$business_admin_id)
  {
    $det_trans = $this->DetailsById($data['txn_id'],'mss_transactions','txn_id');
    $loyalty_points = $det_trans['res_arr']['txn_loyalty_points'] + $data['total_points'];
    $sql = "UPDATE mss_transactions
            SET 
              txn_loyalty_points = ".$this->db->escape($loyalty_points)." 
            WHERE
              txn_id = ".$this->db->escape($data['txn_id']).""; 
    $query = $this->db->query($sql);
    $det_customer = $this->DetailsById($data['customer_id'],'mss_customers','customer_id');
    $customer_points = $det_customer['res_arr']['customer_rewards'] + $data['total_points'];
    $sql = "UPDATE mss_customers
            SET
              customer_rewards = ".$this->db->escape($customer_points)."
            WHERE
              customer_id = ".$this->db->escape($data['customer_id'])."";
    $query = $this->db->query($sql);
    if($query)
    {
      return $this->ModelHelper(true,false,'Update Successfully');
    }
    else
    {
      return $this->ModelHelper(false,true,'Update Fails');
    }
  }

    
	//Get Coversion Ratio
	public function GetConversionRatio($business_admin_id){
		$sql = "SELECT * FROM `mss_loyalty` WHERE mss_loyalty.loyalty_business_admin_id=".$this->db->escape($business_admin_id)." ";

      $query = $this->db->query($sql);
      
      if($query){
          return $this->ModelHelper(true,false,'',$query->result_array());
      }
      else{
          return $this->ModelHelper(false,true,"DB error!");   
      }
    }
    
    //Get Cashier loyalty
	//Business Admin Existing Loyalty
	public function Cashback_Customer($data){
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

    public function UpdateAttendance($data){
		
      $sql="UPDATE mss_emss_attendance SET mss_emss_attendance.out_time=".$this->db->escape($data['out_time'])." WHERE mss_emss_attendance.employee_id=".$this->db->escape($data['employee_id'])." AND mss_emss_attendance.attendance_date=".$this->db->escape($data['attendance_date'])."
     ";
		
        $query = $this->db->query($sql);
        if($this->db->affected_rows() > 0){
          return $this->ModelHelper(true,false);    
        }
        elseif($this->db->affected_rows() == 0){
            return $this->ModelHelper(true,false,"No row updated!");   
        }
        else{
            return $this->ModelHelper(false,true,"Some DB Error!");
        }  
	}
			
			public function ServiceWiseSale($data){
				$sql="SELECT 
				date(mss_transactions.txn_datetime) AS 'billing_date',
				mss_categories.category_name AS 'category',
				mss_sub_categories.sub_category_name AS 'sub_category',
				mss_services.service_name AS 'service',
				SUM(mss_transaction_services.txn_service_quantity) AS 'count',
				SUM(mss_transaction_services.txn_service_discounted_price) AS 'net_amt'
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
						AND mss_services.service_type='service'
						AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
						AND mss_categories.category_business_admin_id = ".$this->db->escape($data['business_admin_id'])."
						AND mss_categories.category_business_outlet_id = ".$this->db->escape($data['business_outlet_id'])."
						AND date(mss_transactions.txn_datetime)= date(now())
				GROUP BY
						date(mss_transactions.txn_datetime),
						mss_categories.category_id,
						mss_sub_categories.sub_category_id,
						mss_services.service_id";
				
					$query = $this->db->query($sql);
					
					if($query->num_rows()){
						return $this->ModelHelper(true,false,'',$query->result_array());
					}
					else{
						return $this->ModelHelper(true,false,'Database Error');   
					} 
				}
				
				public function GetBilledPackagesByTxnId($where){
					$sql = "SELECT 
					mss_package_transactions.package_txn_discount,
					mss_package_transactions.package_txn_value,
					mss_salon_packages.salon_package_name,
					mss_customers.customer_name,
					mss_employees.employee_first_name
				FROM
					mss_customers,
					mss_employees,
					mss_package_transactions,
					mss_transaction_package_details,
					mss_salon_packages
				WHERE 
					mss_package_transactions.package_txn_customer_id=mss_customers.customer_id AND
					mss_package_transactions.package_txn_expert=mss_employees.employee_id AND 
					mss_transaction_package_details.salon_package_id=mss_salon_packages.salon_package_id AND
					mss_transaction_package_details.package_txn_id=mss_package_transactions.package_txn_id AND
					mss_package_transactions.package_txn_id=".$this->db->escape($where)." ";
					//execute the query
					$query = $this->db->query($sql);
					
					if ($query->num_rows() >0){
					   return $this->ModelHelper(true,false,'',$query->result_array());
					} 
					else{
					   return $this->ModelHelper(false,true,"No Data Found!");
					} 
				}
			public function ExpertWiseSale($data){
                // $this->PrettyPrintArray($data);
                // exit;
                    $sql="SELECT                    
                    mss_employees.employee_id AS 'emp_id',
                    mss_employees.employee_mobile,
                    mss_employees.employee_first_name As 'expert_name',
                    SUM(mss_transaction_services.txn_service_quantity) AS 'count',
                    SUM(mss_transaction_services.txn_service_discounted_price) AS 'discounted_amt',
                    SUM(mss_transactions.txn_value) AS 'net_amt'
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
                    AND mss_transactions.txn_status=1
                    AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
                    AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
                    AND date(mss_transactions.txn_datetime) = date(now())
                    GROUP BY 
                        mss_employees.employee_id";
                    
                        $query = $this->db->query($sql);
                        
                        if($query->num_rows()){
                            return $this->ModelHelper(true,false,'',$query->result_array());
                        }
                        else{
                            return $this->ModelHelper(true,false,'Database Error');   
                        } 
                    }


					public function CashReport($data){
						$sql="SELECT
						date(mss_transactions.txn_datetime) 'bill_date',
							SUM(mss_transactions.txn_value) 'total_bill',
							SUM(mss_expenses.amount)'expense'
            FROM
              mss_transactions,
              mss_expenses,
              mss_expense_types
            WHERE
              mss_expenses.expense_type_id= mss_expense_types.expense_type_id
            AND
              mss_expense_types.expense_type_business_admin_id=".$this->db->escape($data['business_admin_id'])." AND	mss_expense_types.expense_type_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."		
            GROUP BY 
              date(mss_transactions.txn_datetime)
            ORDER BY
              date(mss_transactions.txn_datetime) DESC LIMIT 10 ";
              
                $query = $this->db->query($sql);
                
                if($query->num_rows()){
                  return $this->ModelHelper(true,false,'',$query->result_array());
                }
                else{
                  return $this->ModelHelper(true,false,'Database Error');   
                } 
            }

            public function OtcCategory($data){
              $sql="SELECT 
							mss_categories.category_id AS category_id,
							mss_categories.category_name AS category_name 
						FROM 
							mss_categories
						WHERE 
							mss_categories.category_type='Products' AND
							mss_categories.category_for !=1 AND
							mss_categories.category_business_outlet_id=".$this->db->escape($data)." ";
                
                  $query = $this->db->query($sql);
                  
                  if($query->num_rows()){
                    return $this->ModelHelper(true,false,'',$query->result_array());
                  }
                  else{
                    return $this->ModelHelper(true,false,'Database Error');   
                  } 
			  }
			  

	//jitesh
	//Generic function for fetching offers from loyalty Table
	public function GetOffers($where,$table_name)
    {
        // SELECT  offer_id AS offer_id,offers AS offers,points AS points,visits AS visits,offers_status AS offer_status FROM mss_loyalty_offer_integrated WHERE business_outlet_id = 1
      $sql = "SELECT *
                  FROM 
                  $table_name
                   WHERE 
                          business_outlet_id = ".$this->db->escape($where)."
                   AND offers_status = 1";
        $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
          return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else
        {
          return $this->ModelHelper(false,true,'Database Error');
        }
    }
    //Loyalty Transcation
    public function InsertLoyaltyTranscation($data,$points,$table_name)
    {
      if($this->db->insert($table_name,$data)){
          $data = array('insert_id' => $this->db->insert_id());
          return $this->ModelHelper(true,false,'',$data);
      }
      else{
          return $this->ModelHelper(false,true,"Check your inserted query!");
      }
    }
    //Points Calculation
    public function CheckRule($data,$table_name,$where)
    {
      	  $sql = "SELECT *
                  FROM
                  $table_name
                  WHERE "
                  .$where. "=" .$data['business_outlet_id']."
                  AND 
                  rule_status = 1";
          $query = $this->db->query($sql);
          if($query->num_rows() > 0)
          {
              $result = $query->result_array();
							
              foreach ($result as $key=>$value)
              {
                  if($value['rule_type'] == 'Offers Single Rule')
                  {
											$points_generated = ($data['net_amount']*$value['points'])/$value['amount1'];
                      $points_validity  =  $value['rule_validity'];
                      $data = array(
                          'points_generated' => $points_generated,
                          'points_validity' => $points_validity,
                          'rule_type' =>$value['rule_type'],
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Offers Multiple Rule')
                  {
                      $result = $this->MultiplePointsCalculation($data,$where,$table_name,$value['rule_type']);
                      $rule_validity = $value['rule_validity'];
                      $data = array(
                         'points_generated' => $result,
                          'points_validity' => $rule_validity,
                          'rule_type' => $value['rule_type'],
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Cashback Single Rule')
                  {
                      $cashback_generated = ($data['net_amount']/$value['amount1'])*$value['cashback'];
                      $rule_validity = $value['rule_validity'];
                      $data = array(
                          'cashback_generated' => $cashback_generated,
                          'cashback_validity' => $rule_validity,
                          'rule_type' => $value['rule_type'],
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Cashback Multiple Rule')
                  {
                      $cashback_generated = $this->MultipleCashbackCalculation($data,$where,$table_name,$value['rule_type']);
                      $rule_validity = $value['rule_validity'];
                      $data = array(
                         'cashback_generated'=> $cashback_generated,
                          'cashback_validity' =>$rule_validity,
                          'rule_type' => $value['rule_type'],
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Offers LTV Rule')
                  {
                      $result = $this->MultiplePointsCalculation($data,$where,$table_name,$value['rule_type']);
                      $points_validity = $value['rule_validity'];
                      $data = array(
                          'points_generated' =>$result,
                          'points_validity' =>$points_validity,
                          'rule_type' => $value['rule_type'],
                      );    
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Cashback LTV Rule')
                  {
                      $cashback_generated = $this->MultipleCashbackCalculation($data,$where,$table_name,$value['rule_type']);
                      $rule_validity = $value['rule_validity'];
                      $data = array(
                          'cashback_generated'=>$cashback_generated,
                          'cashback_validity'=>$rule_validity,
                          'rule_type' => $value['rule_type'],
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Existing Cashback Rule')
                  {
                      $cashback_generated = ($data['net_amount']/$value['amount1'])*$value['cashback'];
                      $rule_validity = $value['rule_validity'];
                      $data = array(
                          $cashback_generated,
                          $rule_validity,
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else if($value['rule_type'] == 'Cashback Visits')
                  {
                      $rule_validity = $value['rule_validity'];
                      $data = array(
                          $rule_validity
                      );
                      return $this->ModelHelper(true,false,'',$data);
                      die;
                  }
                  else
                  {
                      return $this->ModelHelper(false,true,'No rule Found');
                      die;
                  }
              }
          }
          else
          {
              return $this->ModelHelper(false,true,'No result Found');
              die;
          }
    }
    private function MultiplePointsCalculation($data,$where,$table_name,$rule_type){
        $sql = "SELECT * FROM mss_loyalty_rules WHERE business_outlet_id = ".$this->db->escape($data['business_outlet_id'])." AND rule_type = ".$this->db->escape($rule_type)." AND ".$this->db->escape($data['net_amount'])." BETWEEN amount1 AND amount2";
        $query = $this->db->query($sql);
      //   print_r($query);
      //     exit;
      // print_r($query);
      //  exit;
        if($query->num_rows() > 0)
        {
          $result = $query->result_array();
          // print_r($result);
          // exit;
          foreach($result as $key=>$value)
          {
              $points_generated = $value['points'];
              return $points_generated;
          }
        }
        else
        {
            return ($points_generated=0);
        }
    }
    private function MultipleCashbackCalculation($data,$where,$table_name,$rule_type){
        $sql = "SELECT * FROM $table_name WHERE $where = ".$this->db->escape($data['business_outlet_id'])." AND rule_type = ".$this->db->escape($rule_type)." AND ".$this->db->escape($data['net_amount'])." BETWEEN amount1 AND amount2";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            $result = $query->result_array();
            foreach($result as $key=>$value)
            {
                $cashback_generated = $value['cashback'];
                return ($cashback_generated);
            }
        }
        else
        {
           return ($cashback_generated = 0); 
        }
    }
    public function UpdateCustomerPoints($data,$table_name,$where)
    {
        $sql = "UPDATE mss_customers SET customer_rewards = ".$this->db->escape($data['customer_rewards'])." where customer_id = ".$this->db->escape($data['customer_id']);
        $query = $this->db->query($sql);
        if($query > 0){
            return $this->ModelHelper(true,false);    
        }
        elseif($query == 0){
            return $this->ModelHelper(true,false,"No row updated!");   
        }
        else{
            return $this->ModelHelper(false,true,"Some DB Error!");
        }    
                
    }
	//Pritam Code for EMSS AttendANCE
	public function CalculateAttendance()
	{
		$query=$this->db->query("SELECT mss_emss_attendance.employee_id,mss_employees.employee_first_name,
		mss_employees.employee_last_name,mss_employees.employee_mobile,
		mss_employees.employee_address,
		mss_emss_attendance.status,
		TIME_FORMAT(mss_emss_attendance.in_time,'%H:%i:%s') as 'in_time',
		TIME_FORMAT(mss_emss_attendance.out_time,'%H:%i:%s') as 'out_time',
		mss_emss_attendance.working_hours as 'working_hours',
		mss_emss_attendance.over_time as 'over_time'
		FROM mss_emss_attendance,mss_employees 
		WHERE
		mss_emss_attendance.employee_outlet_id=".$this->session->userdata['logged_in']['business_outlet_id']."
		AND mss_employees.employee_id=mss_emss_attendance.employee_id
		AND mss_emss_attendance.attendance_date=CURRENT_DATE
		");
		return $result=$query->result_array();
	}
	public function CheckAttendance($data)
	{
		$sql="SELECT * from mss_emss_attendance where employee_id=".$this->db->escape($data['employee_id'])." AND attendance_date=date(now()) ";
		$query = $this->db->query($sql);
	   
		
		if($query->num_rows()>0){
			return $this->ModelHelper(true,false,'Attendance Already Exist.');
		  }
		  else{
			return $this->ModelHelper(false,true,'');
		  }
			
		//  $sql="SELECT * from mss_emss_attendance,mss_employees  where mss_employees.employee_business_outlet=1
		//  GROUP BY mss_employees.employee_id";   
		// $query=$this->db->query($sql);
		// return $result=$query->result_array
	}
	public function GetWorkingHoursOutlet($data){
		$query=$this->db->query("SELECT working_hours FROM mss_business_outlets WHERE mss_business_outlets.business_outlet_id=".$this->db->escape($data['employee_outlet_id'])."");
		return $result = $query->result_array(); 
	}
	
	public function CheckAttendanceOutTime($data)
	{
		$query=$this->db->query("SELECT in_time,out_time from mss_emss_attendance where employee_id=".$this->db->escape($data['employee_id'])." AND attendance_date=date(now()) ");
		 return $result = $query->result_array();
	   
	}
	public function CalculateOverTime($data)
	{
		$query=$this->db->query("SELECT in_time from mss_emss_attendance where employee_id=".$this->db->escape($data['employee_id'])." AND attendance_date=date(now()) ");
		 return $result = $query->result_array();
	   
	}
	public function UpdateWorkingHours($data)
	{
		$sql="UPDATE mss_emss_attendance SET mss_emss_attendance.working_hours=".$this->db->escape($data['working_hours']).",mss_emss_attendance.over_time=".$this->db->escape($data['over_time'])." WHERE mss_emss_attendance.employee_id=".$this->db->escape($data['employee_id'])." AND mss_emss_attendance.attendance_date=date(now())
		";
		   
		   $query = $this->db->query($sql);
		   if($this->db->affected_rows() > 0){
			 return $this->ModelHelper(true,false);    
		   }
		   elseif($this->db->affected_rows() == 0){
			   return $this->ModelHelper(true,false,"No row updated!");   
		   }
		   else{
			   return $this->ModelHelper(false,true,"Some DB Error!");
		   }  
   
	}
	//Attendance Report
	public function GetAttendanceReport($data)
	{
		$sql="SELECT mss_employees.employee_first_name,mss_employees.employee_last_name,mss_emss_attendance.attendance_date,
		TIME_FORMAT(mss_emss_attendance.in_time,'%H:%i:%s') as 'in_time',
		TIME_FORMAT(mss_emss_attendance.out_time,'%H:%i:%s') as 'out_time',
		TIME_FORMAT(mss_emss_attendance.working_hours,'%H:%i:%s') as 'working_hours',
		TIME_FORMAT(mss_emss_attendance.over_time,'%H:%i:%s') as 'over_time'
		from mss_employees,mss_emss_attendance 
		where mss_emss_attendance.employee_outlet_id=".$this->db->escape($data['employee_outlet_id'])."
		AND mss_emss_attendance.employee_business_admin_id=".$this->db->escape($data['employee_business_admin_id'])."
		AND MONTH(mss_emss_attendance.attendance_date)=month(CURRENT_DATE)
		AND YEAR(mss_emss_attendance.attendance_date)=year(CURRENT_DATE)
		AND mss_employees.employee_id=mss_emss_attendance.employee_id
		order by mss_emss_attendance.attendance_date";
		$query = $this->db->query($sql);
		return $result = $query->result_array();
	}
	
	//Get Billed Services
	public function GetBilledServicesByTxnId($where){
		$sql = "SELECT mss_transactions.txn_id,
		mss_services.service_name,
		mss_transactions.txn_customer_id,
        mss_transactions.txn_datetime,
        mss_transactions.txn_loyalty_points,
		mss_employees.employee_first_name,
        mss_employees.employee_last_name,
		mss_transactions.txn_value,
		mss_transaction_services.txn_service_discount_percentage AS 'disc1',
		mss_transaction_services.txn_service_discount_absolute AS 'disc2',
		mss_transaction_services.txn_service_service_id AS 'service_id',
		mss_transaction_services.txn_service_quantity,
		mss_transaction_services.txn_service_discounted_price,
		mss_transaction_settlements.txn_settlement_way,
		mss_transaction_settlements.txn_settlement_payment_mode,
		mss_transaction_settlements.txn_settlement_amount_received
	FROM
		mss_transactions,
		mss_transaction_settlements,
		mss_transaction_services,
		mss_services,
		mss_employees
	WHERE 
		mss_transaction_services.txn_service_status=1 AND
		mss_services.service_id= mss_transaction_services.txn_service_service_id AND
		mss_transaction_settlements.txn_settlement_txn_id=mss_transactions.txn_id AND
		mss_transaction_services.txn_service_txn_id= mss_transactions.txn_id AND
		 mss_transaction_services.txn_service_expert_id = mss_employees.employee_id AND
	mss_transactions.txn_id=".$this->db->escape($where)." GROUP BY mss_transaction_services.txn_service_id";

        //execute the query
        $query = $this->db->query($sql);
        
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
	}
	
	//
	public function ExpertWisePackageSale($data){
		$sql="SELECT mss_employees.employee_id as 'emp_id',
		mss_employees.employee_mobile,
		mss_employees.employee_first_name As 'expert_name',
		SUM(mss_package_transaction_settlements.amount_received) AS package_sales
		FROM
		mss_employees,
		mss_package_transactions,
		mss_package_transaction_settlements,
		mss_customers
		WHERE
		mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
		mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
		AND mss_package_transactions.package_txn_expert=mss_employees.employee_id
		AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
		AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
		AND date(mss_package_transactions.datetime) = date(now())
		GROUP BY 
				mss_employees.employee_id ";
		
				$query = $this->db->query($sql);
				
				if($query->num_rows()){
						return $this->ModelHelper(true,false,'',$query->result_array());
				}
				else{
						return $this->ModelHelper(true,false,'Database Error');   
				} 
		}

		public function GetTodaysPackageTransaction($data){
			$sql="SELECT 
			mss_package_transactions.package_txn_id AS 'bill_no',
			mss_package_transactions.package_txn_unique_serial_id AS 'Txn_id',
			date(mss_package_transactions.datetime) AS 'billing_date',
			mss_customers.customer_mobile AS 'mobile',
			mss_customers.customer_name AS 'name',
			(mss_package_transactions.package_txn_discount+mss_package_transactions.package_txn_value) AS 'mrp_amt',
			mss_package_transactions.package_txn_discount AS 'discount',
			mss_package_transactions.package_txn_value AS 'net_amt',
			mss_package_transaction_settlements.settlement_way AS 'settlement_way',
			mss_package_transaction_settlements.payment_mode AS 'payment_way'
			FROM
			mss_package_transactions,
			mss_package_transaction_settlements,
			mss_customers,
			mss_employees
			 WHERE
			mss_package_transactions.package_txn_id=mss_package_transaction_settlements.package_txn_id AND
			mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
			AND mss_package_transactions.package_txn_cashier =mss_employees.employee_id
			AND date(mss_package_transactions.datetime) = date(now())
			AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
			AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."";
			
			$query = $this->db->query($sql);
			
			if($query->num_rows()){
					return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
					return $this->ModelHelper(true,false,'Database Error');   
			} 
	}
	
	public function ProductsSalesToday(){
        $sql = "SELECT
      mss_categories.category_name,
      mss_sub_categories.sub_category_name,
      mss_services.service_name,
      SUM(mss_transaction_services.txn_service_discounted_price) AS 'txn_service_discounted_price', 
      COUNT(mss_transaction_services.txn_service_quantity) AS 'count'
      FROM
                mss_transactions,
                mss_transaction_services,
                mss_employees,
                mss_customers,
                mss_categories,
                mss_sub_categories,
                mss_services
        WHERE
                mss_transaction_services.txn_service_txn_id =mss_transactions.txn_id 
                AND mss_transaction_services.txn_service_status=1
                AND mss_transactions.txn_status=1
                AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
                AND mss_transaction_services.txn_service_service_id = mss_services.service_id
                AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                AND mss_services.inventory_type = 'Retail Product'
                AND mss_transactions.txn_customer_id = mss_customers.customer_id
                AND employee_business_outlet=".$this->session->userdata['logged_in']['business_outlet_id']."
                AND employee_business_admin=".$this->session->userdata['logged_in']['business_admin_id']."
                AND date(mss_transactions.txn_datetime) = CURRENT_DATE 
                GROUP BY
                mss_services.service_id";
        //execute the query
        $query = $this->db->query($sql);
        
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
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
		AND mss_services.inventory_type !='Raw Material'
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_categories.category_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
        AND mss_categories.category_business_outlet_id = ".$this->session->userdata['logged_in']['business_outlet_id']."
        
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
    //txn History Details
    public function TxnHistory($data){
        $sql = "SELECT * FROM mss_transactions_replica 
            WHERE 
                mss_transactions_replica.txn_outlet_id=".$this->db->escape($data['outlet_id'])." AND
                mss_transactions_replica.txn_business_admin_id=".$this->db->escape($data['business_admin_id'])."
            ORDER BY mss_transactions_replica.txn_datetime DESC LIMIT 10";
        //execute the query
        $query = $this->db->query($sql);
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
    }
    public function TxnHistoryPackages(){
        $sql = "SELECT * FROM mss_package_transactions_replica where master_admin_id=".$this->session->userdata['logged_in']['master_admin_id']." 
        ORDER BY datetime DESC LIMIT 10";
        //execute the query
        $query = $this->db->query($sql);
        if ($query->num_rows() >0){
           return $this->ModelHelper(true,false,'',$query->result_array());
        } 
        else{
           return $this->ModelHelper(false,true,"No Data Found!");
        } 
    }
    public function TxnHistoryServices(){
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
    public function TxnHistoryProduct(){
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
    public function TxnBirthDay($data){
        $sql = "select mss_transactions_replica.txn_customer_name,
        mss_transactions_replica.txn_customer_number,
        mss_customers.customer_dob,
        mss_customers.customer_id,
        sum(mss_transactions_replica.txn_value) as 'amt',
        max(date(mss_transactions_replica.txn_datetime)) as 'last_visit'
        from mss_transactions_replica,mss_customers 
        where mss_transactions_replica.txn_outlet_id=".$this->db->escape($data['outlet_id'])." 
        AND month(date(mss_customers.customer_dob))=month(".$this->db->escape($data['month']).")    
        AND mss_transactions_replica.txn_customer_id=mss_customers.customer_id
        group BY mss_transactions_replica.txn_customer_id
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
    public function TxnAnniverDay($data){
        $sql = "select mss_transactions_replica.txn_customer_name,
        mss_transactions_replica.txn_customer_number,
        mss_customers.customer_doa,
        mss_customers.customer_id,
        sum(mss_transactions_replica.txn_value) as 'amt',
        max(date(mss_transactions_replica.txn_datetime)) as 'last_visit'
        from mss_transactions_replica,mss_customers 
        where mss_transactions_replica.txn_outlet_id=".$this->db->escape($data['outlet_id'])." 
        AND month(date(mss_customers.customer_doa))=month(".$this->db->escape($data['month']).")    
        AND mss_transactions_replica.txn_customer_id=mss_customers.customer_id
        group BY mss_transactions_replica.txn_customer_id
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
    public function GetCompleteCustomerHistory($customer_id){
        $this->db->select('*');
        $this->db->from('mss_customers');
        $this->db->where('customer_id',$customer_id);
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() == 1){
            $result = array();
            $result = $query->row_array();
            $sql = "SELECT  mss_transactions_replica.txn_value,
            mss_transactions_replica.txn_discount,
            date(mss_transactions_replica.txn_datetime) AS BillDate 
            FROM 
            mss_transactions_replica
            WHERE 
            mss_transactions_replica.txn_customer_id = ".$this->db->escape($customer_id)."
            ORDER BY mss_transactions_replica.txn_datetime DESC LIMIT 4";            
            
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
            SUM(mss_transactions_replica.txn_value)'total',
            COUNT(mss_transactions_replica.txn_id)'total_visit',
            CAST(AVG(mss_transactions_replica.txn_value) AS DECIMAL(10,2))'avg_value',
            MAX(date(mss_transactions_replica.txn_datetime))'last_visit'
        FROM
            mss_transactions_replica
        WHERE
            mss_transactions_replica.txn_customer_id=".$this->db->escape($customer_id)." ";            
            
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
     //1-04-2020
    public function ServiceVisitSales(){
        $sql = "SELECT COUNT(mss_transactions.txn_id) as 'visit',
        SUM(mss_transactions.txn_value) as 'service' 
		FROM
			mss_transactions,
			mss_customers,
			mss_employees 
		WHERE 
			date(mss_transactions.txn_datetime) = CURRENT_DATE 
			AND mss_transactions.txn_customer_id = mss_customers.customer_id
			AND mss_transactions.txn_cashier= mss_employees.employee_id
			AND mss_transactions.txn_status=1
			AND mss_employees.employee_business_admin=".$this->session->userdata['logged_in']['business_admin_id']."
			AND mss_employees.employee_business_outlet=".$this->session->userdata['logged_in']['business_outlet_id']."
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
    public function PackageVisitSales(){
        $sql = "SELECT count(mss_package_transactions.package_txn_id) as 'visit',
        SUM(mss_package_transactions.package_txn_value)  as 'packages'
        FROM mss_package_transactions,
        mss_customers,
        mss_employees
        where date(mss_package_transactions.datetime) = date(now())
        AND mss_package_transactions.package_txn_cashier= mss_employees.employee_id
        AND mss_package_transactions.package_txn_customer_id=mss_customers.customer_id
        AND mss_employees.employee_business_admin=".$this->session->userdata['logged_in']['business_admin_id']."
        AND mss_employees.employee_business_outlet=".$this->session->userdata['logged_in']['business_outlet_id']."
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
    public function GetAllAppointmentsCount(){
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
                    AND mss_customers.customer_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
                    AND mss_customers.customer_business_outlet_id = ".$this->session->userdata['logged_in']['business_outlet_id']."
                    AND mss_employees.employee_business_outlet = ".$this->session->userdata['logged_in']['business_outlet_id']."
                    AND mss_employees.employee_business_admin = ".$this->session->userdata['logged_in']['business_admin_id']."
                    AND mss_appointments.appointment_status = 1 ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
        return $this->ModelHelper(false,true,"DB error!");   
        }   
    }
    //11-04-2020
    public function SearchProduct($search_term,$inventory_type,$business_admin_id,$business_outlet_id){
        $sql = "SELECT 
                    mss_services.*,
					round(mss_services.service_price_inr+mss_services.service_price_inr*mss_services.service_gst_percentage*(.01)) AS 'mrp',
                    mss_categories.category_name,
                    mss_sub_categories.sub_category_name 
                FROM 
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($business_admin_id)."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($business_outlet_id)."
                    AND mss_services.service_is_active = TRUE
                    AND (mss_services.service_name LIKE '$search_term%' OR  mss_services.barcode LIKE '$search_term%')
                    AND mss_services.service_type = 'otc'
                    AND mss_services.inventory_type =  ".$this->db->escape($inventory_type)."
                ORDER BY mss_services.service_name LIMIT 15";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ProductDetails($search_term,$business_admin_id,$business_outlet_id){
        // $this->PrintArray($search_term);
        $sql = "SELECT 
                    mss_services.*,
                    mss_categories.category_name,mss_sub_categories.sub_category_name 
                FROM 
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($business_admin_id)."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($business_outlet_id)."
                    AND mss_services.service_is_active = TRUE
                    AND mss_services.service_name = ".$this->db->escape($search_term)."
                   ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ProductDetail($search_term,$inventory_type,$business_admin_id,$business_outlet_id){
        // $this->PrintArray($inventory_type);
        $sql = "SELECT 
                    mss_services.*,
                    mss_categories.category_name,mss_sub_categories.sub_category_name 
                FROM 
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($business_admin_id)."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($business_outlet_id)."
                    AND mss_services.service_is_active = TRUE
                    AND mss_services.service_name = ".$this->db->escape($search_term)."
                    ORDER BY mss_services.service_name LIMIT 15";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    // public function CheckOTCStockExists($where){
    //     $this->db->select('*');
    //     $this->db->from('mss_inventory');
    //     $this->db->where('service_id',$where['service_id']);
    //     $query = $this->db->get();
    //     if($query->num_rows() === 0){
    //         return $this->ModelHelper(false,true);
    //     }
    //     else if($query->num_rows() === 1){
    //         return $this->ModelHelper(true,false);
    //     }   
    // }
    public function UpdateOTCStocknew($data){
        $stock = $data['sku_count'];
        $this->db->set('sku_count','sku_count + '.(int)$stock.'',FALSE);
        $this->db->set('expiry',$data['expiry'],FALSE);
        $this->db->where('service_id', $data['service_id']); 
		$this->db->update('mss_inventory');
		// $this->PrintArray($this->db->affected_rows());
        if($this->db->affected_rows() > 0){
            return $this->ModelHelper(true,false);    
        }
        elseif($this->db->affected_rows() === 0){
            return $this->ModelHelper(true,false,"No row updated!");   
        }
        else{
            return $this->ModelHelper(false,true,"Some DB Error!");
        }  
    }
    public function SearchProductDetails($search_term,$inventory_type,$business_admin_id,$business_outlet_id){
        $sql = "SELECT 
                    mss_services.*,
                    mss_categories.category_name 
                FROM 
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->db->escape($business_admin_id)."
                    AND mss_categories.category_business_outlet_id = ".$this->db->escape($business_outlet_id)."
                    AND mss_services.service_is_active = TRUE
                    AND (mss_services.service_name LIKE '$search_term%' OR  mss_services.barcode LIKE '$search_term%')
                    AND mss_services.service_type = 'otc'
                    AND mss_services.inventory_type =  ".$this->db->escape($inventory_type)."
                ORDER BY mss_services.service_name LIMIT 15";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ProductByName($data){
        $sql = "SELECT 
                    mss_services.*,
                    mss_categories.category_name 
                FROM 
                    mss_services,
                    mss_sub_categories,
                    mss_categories
                WHERE
                    mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
                    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
                    AND mss_categories.category_business_admin_id = ".$this->session->userdata['logged_in']['business_admin_id']."
                    AND mss_categories.category_business_outlet_id = ".$this->session->userdata['logged_in']['business_outlet_id']."
                    AND mss_services.service_is_active = TRUE
                    AND mss_services.service_name = ".$this->db->escape($data['service_name'])."
                    AND mss_services.service_type = 'otc'
                   ";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function InventoryStock(){
        $sql = "SELECT 
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
					mss_categories.category_business_admin_id= ".$this->session->userdata['logged_in']['business_admin_id']." AND
					mss_categories.category_business_outlet_id= ".$this->session->userdata['logged_in']['business_outlet_id']." 
				GROUP BY mss_services.service_id";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
     //15-04
    public function InventoryReports(){
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
        AND mss_inventory.business_admin_id=".$this->session->userdata['logged_in']['business_admin_id']."
        AND mss_inventory.outlet_id=".$this->session->userdata['logged_in']['business_outlet_id']."";
        
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
    public function ServiceDetail($id){
        $sql = "SELECT * FROM mss_services,mss_sub_categories,mss_categories
        where mss_services.service_id=$id
        AND mss_services.service_sub_category_id=mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id=mss_categories.category_id";
        
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }
     //18/04/2020
    public function TxnDetailsCustService($where,$q){
        $sql="SELECT DISTINCT txn_customer_number,txn_customer_name FROM mss_transactions_replica 
            WHERE 
            master_admin_id=".$this->db->escape($where['master_admin_id'])."
            AND (mss_transactions_replica.txn_customer_number LIKE '%$q%' OR mss_transactions_replica.txn_customer_name LIKE '%$q%')";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(true,false,'Database Error');   
            } 
    }
   public function GetCustServiceData($where){
        $sql="SELECT DATE_FORMAT(txn_datetime,'%Y-%m-%d') as 'date',mss_transactions_replica.* FROM mss_transactions_replica 
            WHERE 
            master_admin_id=".$this->session->userdata['logged_in']['master_admin_id']."
            AND mss_transactions_replica.txn_customer_number = ".$this->db->escape($where['customer_no'])." ORDER BY txn_datetime DESC";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(true,false,'Database Error');   
            } 
    }
    // packages
    public function TxnDetailsCustPackages($where,$q){
        $sql="SELECT DISTINCT customer_number,customer_name FROM mss_package_transactions_replica 
        WHERE 
        master_admin_id=".$this->db->escape($where['master_admin_id'])."
        AND (mss_package_transactions_replica.customer_number LIKE '$q%' OR mss_package_transactions_replica.customer_name LIKE '$q%')";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(true,false,'Database Error');   
        } 
    }
    public function GetCustPackagesData($where){
        $sql="SELECT DATE_FORMAT(mss_package_transactions_replica.datetime,'%Y-%m-%d') as 'date',mss_package_transactions_replica.* FROM mss_package_transactions_replica
            WHERE 
            master_admin_id=".$this->db->escape($where['master_admin_id'])."
            AND mss_package_transactions_replica.customer_number = ".$this->db->escape($where['customer_no'])." ORDER BY datetime DESC";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(true,false,'Database Error');   
            } 
    }

    //preferred services
    public function TxnDetailsCustPS($where,$q){
        $sql="SELECT DISTINCT cust_mobile,cust_name FROM mss_transaction_services_replica 
        WHERE 
        master_admin_id=".$this->db->escape($where['master_admin_id'])."
        AND mss_transaction_services_replica.service_type='service'
        AND (mss_transaction_services_replica.cust_mobile LIKE '$q%' OR mss_transaction_services_replica.cust_name LIKE '$q%')";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(true,false,'Database Error');   
        } 
    }
    public function GetCustServicePS($where){
        $sql="select cust_name,cust_mobile,service_name,sum(txn_service_quantity) as 'count',max(txn_datetime) as 'last_visit',business_outlet_name 
        from mss_transaction_services_replica 
        where 
        master_admin_id=".$this->db->escape($where['master_admin_id'])."
        AND service_type='service'
        AND cust_mobile=".$this->db->escape($where['customer_no'])."
        group by service_name,cust_mobile
        ORDER BY count DESC";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(true,false,'Database Error');   
            } 
    }
    // Prefferd Product
    public function TxnDetailsCustPP($where,$q){
        $sql="SELECT DISTINCT cust_mobile,cust_name FROM mss_transaction_services_replica 
        WHERE 
        master_admin_id=".$this->db->escape($where['master_admin_id'])."
        AND mss_transaction_services_replica.service_type='otc'
        AND (mss_transaction_services_replica.cust_mobile LIKE '$q%' OR mss_transaction_services_replica.cust_name LIKE '$q%')";
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(true,false,'Database Error');   
        } 
    }
    public function GetCustServicePP($where){
        $sql="select cust_name,cust_mobile,service_name,sum(txn_service_quantity) as 'count',max(txn_datetime) as 'last_visit',business_outlet_name 
        from mss_transaction_services_replica 
        where 
        master_admin_id=".$this->db->escape($where['master_admin_id'])."
        AND service_type='otc'
        AND cust_mobile=".$this->db->escape($where['customer_no'])."
        group by service_name,cust_mobile
        ORDER BY count DESC";
            $query = $this->db->query($sql);
            if($query){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(true,false,'Database Error');   
            } 
    }
    //27-05-2020
    public function GetTopServicesCustomer($data){
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
        AND mss_categories.category_business_outlet_id = ".$this->session->userdata['logged_in']['business_outlet_id']."
        AND mss_transactions.txn_customer_id=$data
        GROUP BY 
        mss_services.service_id 
        ORDER BY 
        COUNT(mss_transaction_services.txn_service_service_id) DESC
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
    
    
    	//Last 50 transactions
	public function LastFiftyTransaction(){
		$sql="SELECT 
		mss_transactions.txn_id AS 'bill_no',
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
		AND mss_transactions.txn_status=1
		AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
		AND mss_transactions.txn_cashier= mss_employees.employee_id
		AND mss_employees.employee_business_admin = ".$this->session->userdata['logged_in']['business_admin_id']."
		AND mss_employees.employee_business_outlet = ".$this->session->userdata['logged_in']['business_outlet_id']."		
		ORDER BY 
        mss_transactions.txn_id DESC LIMIT 100 ";

        $query = $this->db->query($sql);
        
        if($query->num_rows()){
                $result1 = $query->result_array();
             $sql = "SELECT
                    mss_package_transactions.package_txn_id AS 'bill_no',
                    mss_package_transactions.package_txn_unique_serial_id AS 'txn_id',
                    date(mss_package_transactions.datetime) AS 'billing_date',
                    mss_customers.customer_mobile AS 'mobile',
                    mss_customers.customer_name AS 'name',            
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
                    AND mss_salon_packages.business_admin_id =  ".$this->db->escape($this->session->userdata['logged_in']['business_admin_id'])."
                    AND mss_salon_packages.business_outlet_id =  ".$this->db->escape($this->session->userdata['logged_in']['business_outlet_id'])."                    
                    ORDER BY
                        mss_package_transactions.package_txn_id desc limit 100";                        
                    $query = $this->db->query($sql); 
                    $result2 = $query->result_array();
                    $result = array_merge($result1,$result2);
                    // echo "<pre>";
                    // print_r($result);
                    // die;
          return $this->ModelHelper(true,false,'',$result);
        }
        else{
          return $this->ModelHelper(true,false,'Database Error');   
		} 
	}

	public function AvailableStock($data){
		$sql="SELECT mss_services.*, 
			inventory_stock.*,
			mss_business_outlets.business_outlet_name
			FROM	
			inventory_stock,
			mss_services,
			mss_business_outlets
			WHERE inventory_stock.stock_service_id = mss_services.service_id AND
			mss_business_outlets.business_outlet_id= ".$this->db->escape($data['business_outlet_id'])." AND
			inventory_stock.stock_outlet_id=".$this->db->escape($data['business_outlet_id'])." ";
        $query = $this->db->query($sql);

        if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());            
        }
        else{
            return $this->ModelHelper(false,true,"Product not Available in stock.");
        } 
	}


	public function IncomingStock($data){
		$sql="SELECT inventory_transfer.*,
		inventory_transfer_data.*, 
		mss_business_outlets.business_outlet_name AS 'source',
		mss_business_outlets.business_outlet_name AS 'destination'
		FROM inventory_transfer, inventory_transfer_data, mss_business_outlets
		WHERE inventory_transfer_data.inventory_transfer_id= inventory_transfer.inventory_transfer_id AND inventory_transfer_data.transfer_status=0 AND 
		inventory_transfer.destination_name= mss_business_outlets.business_outlet_id AND 
		inventory_transfer.destination_name= ".$this->db->escape($data['business_outlet_id'])." AND mss_business_outlets.business_outlet_id= ".$this->db->escape($data['business_outlet_id'])." ";
        $query = $this->db->query($sql);

        if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());            
        }
        else{
            return $this->ModelHelper(false,true,"Product not Available in stock.");
        } 
	}
	public function OutgoingStock($data){
		$sql="SELECT inventory_transfer.*,inventory_transfer_data.* FROM inventory_transfer, inventory_transfer_data
		WHERE inventory_transfer_data.inventory_transfer_id= inventory_transfer.inventory_transfer_id  AND inventory_transfer.business_outlet_id= ".$this->db->escape($data['business_outlet_id'])." ";
        $query = $this->db->query($sql);

        if($query){
			return $this->ModelHelper(true,false,'',$query->result_array());            
        }
        else{
            return $this->ModelHelper(false,true,"Product not Available in stock.");
        } 
	}

	public function CheckStockExist($where){
        $this->db->select('*');
        $this->db->from('inventory_stock');
        $this->db->where('stock_service_id',$where['stock_service_id']);
        $this->db->where('stock_outlet_id',$where['stock_outlet_id']);
        $query = $this->db->get();

        if($query->num_rows() === 0){
            return $this->ModelHelper(false,true);
        }
        else if($query->num_rows() === 1){
            return $this->ModelHelper(true,false);
        }   
	}
	public function UpdateInventoryStock($data){
		$sql="UPDATE inventory_stock 
		SET inventory_stock.total_stock= (inventory_stock.total_stock +  ".$data['total_stock']."),
		inventory_stock.updated_on= ".$this->db->escape($data['updated_on'])." 
		WHERE inventory_stock.stock_service_id=".$this->db->escape($data['stock_service_id'])." AND inventory_stock.stock_outlet_id=".$this->db->escape($data['stock_outlet_id'])." ";
		   
		   $query = $this->db->query($sql);
		   if($this->db->affected_rows() > 0){
			 return $this->ModelHelper(true,false);    
		   }
		   elseif($this->db->affected_rows() == 0){
			   return $this->ModelHelper(true,false,"No row updated!");   
		   }
		   else{
			   return $this->ModelHelper(false,true,"Some DB Error!");
		   } 
	}

	public function UpdateSenderInventoryStock($data){
		$sql="UPDATE inventory_stock 
		SET inventory_stock.total_stock= (inventory_stock.total_stock -  ".$data['total_stock']."),
		inventory_stock.updated_on= ".$this->db->escape($data['updated_on'])." 
		WHERE inventory_stock.stock_service_id=".$this->db->escape($data['stock_service_id'])." AND inventory_stock.stock_outlet_id=".$this->db->escape($data['stock_outlet_id'])." ";
		   
		   $query = $this->db->query($sql);
		   if($this->db->affected_rows() > 0){
			 return $this->ModelHelper(true,false);    
		   }
		   elseif($this->db->affected_rows() == 0){
			   return $this->ModelHelper(true,false,"No row updated!");   
		   }
		   else{
			   return $this->ModelHelper(false,true,"Some DB Error!");
		   } 
	}

	public function CheckStockExistForTransfer($where){
        $sql="SELECT * FROM inventory_stock WHERE inventory_stock.stock_service_id =".$this->db->escape($where['stock_service_id'])." AND inventory_stock.total_stock > ".$this->db->escape($where['total_stock'])." AND inventory_stock.stock_outlet_id= ".$this->db->escape($where['stock_outlet_id'])." ";
        $query = $this->db->query($sql);

        if($query->num_rows() === 0){
            return $this->ModelHelper(false,true,"Product not Available in stock Or Less Stock.");
        }
        else if($query->num_rows() === 1){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }   
	}


	public function UpdateInventoryStockTransfer($data){
		$sql="UPDATE inventory_stock 
		SET inventory_stock.total_stock= (inventory_stock.total_stock -  ".$data['total_stock']."),
		inventory_stock.updated_on= ".$this->db->escape($data['updated_on'])." 
		WHERE inventory_stock.stock_service_id=".$this->db->escape($data['stock_service_id'])." AND inventory_stock.stock_outlet_id=".$this->db->escape($data['stock_outlet_id'])." ";
		   
		   $query = $this->db->query($sql);
		   if($this->db->affected_rows() > 0){
			 return $this->ModelHelper(true,false);    
		   }
		   elseif($this->db->affected_rows() == 0){
			   return $this->ModelHelper(true,false,"No row updated!");   
		   }
		   else{
			   return $this->ModelHelper(false,true,"Some DB Error!");
		   } 
	}


}

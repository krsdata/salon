<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SuperAdminModel extends CI_Model {
	

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

    //public function for logging in the Admin-admin to dashboard    
    public function SuperAdminLogin($data) {
        $this->db->select('*');
        $this->db->from('mss_super_admin');
        $this->db->where('super_admin_email',$data['super_admin_email']);
        $this->db->limit(1);
        
        $query = $this->db->get();

        if ($query->num_rows() == 1){
            return $this->ModelHelper(true,false);
        }
        else{
            return $this->ModelHelper(false,true,'No such business admin exists!');
        }
    }
    

    public function SuperAdminByEmail($email) {

        $this->db->select('*');
        $this->db->from('mss_super_admin');
        $this->db->where('super_admin_email',$email);
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
		if($this->db->insert($table_name,$data)){
			$data = array('insert_id' => $this->db->insert_id());
			return $this->ModelHelper(true,false,'',$data);
		}
		else{
			return $this->ModelHelper(false,true,"Check your inserted query!");
		}
	}

   //
   public function GetModule(){
		$sql = "SELECT mss_packages.package_id,mss_packages.package_name FROM mss_packages ";

        //execute the query
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
   }

   //Get Purchased Module
   public function GetAdminModule($where){
		$sql = "SELECT * FROM mss_business_admin_packages WHERE mss_business_admin_packages.business_admin_id=".$this->db->escape($where['business_admin_id'])." ";

        //execute the query
        $query = $this->db->query($sql);
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
   }
   //Update Admin Module
  public function UpdateAdminModule($data){
		$sql = "UPDATE 
		mss_business_admin_packages 
		SET 
		package_expiry_date=".$this->db->escape($data['package_expiry_date'])." 
		WHERE 
		mss_business_admin_packages.package_id=".$this->db->escape($data['package_id'])." 
		AND
		mss_business_admin_packages.business_admin_id=".$this->db->escape($data['business_admin_id'])." ";

		//execute the query
		$query = $this->db->query($sql);
		if($query){
			return $this->ModelHelper(true,false,'Module Updated');
		}
		else{
			return $this->ModelHelper(false,true,"DB error!");   
		}
  }

   //
  public function GetCustomerData($data){
		$sql = "SELECT mss_customers.customer_name,
								mss_customers.customer_mobile
							FROM
								mss_customers
							WHERE
								mss_customers.customer_business_admin_id=".$this->db->escape($data['business_outlet_business_admin'])."
							AND
								mss_customers.customer_business_outlet_id=".$this->db->escape($data['business_outlet_id'])."
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
	 public function OutletDetails($data){
		$sql = "SELECT 
		mss_business_outlets.*,
		MAX(date(mss_transactions.txn_datetime))'last_txn_date',
		COUNT(mss_transactions.txn_id)'total_bill'
		FROM
			mss_business_outlets,
			mss_customers,
			mss_transactions
		WHERE
			mss_business_outlets.business_outlet_status=1
		AND
			mss_transactions.txn_customer_id=mss_customers.customer_id
		AND	
			mss_customers.customer_business_outlet_id= mss_business_outlets.business_outlet_id
		AND	
			mss_customers.customer_business_admin_id=".$this->db->escape($data['business_outlet_business_admin'])."
		GROUP BY
			mss_business_outlets.business_outlet_id";
	
			$query = $this->db->query($sql);
			
			if($query){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			}
		}
    // Active Admin Details
		public function ActiveAdminDetails(){
		$sql = "SELECT 
		mss_business_admin.*,
		mss_business_outlets.business_outlet_name,
		MAX(date(mss_transactions.txn_datetime))'last_txn_date',
		COUNT(mss_transactions.txn_id)'total_bill'
		FROM
			mss_business_admin,
			mss_business_outlets,
			mss_transactions,
			mss_customers
		WHERE
			mss_business_outlets.business_outlet_business_admin= mss_business_admin.business_admin_id
		AND
			mss_transactions.txn_customer_id=mss_customers.customer_id
		AND
			mss_customers.customer_business_admin_id=mss_business_admin.business_admin_id
		AND	
			mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id
		GROUP BY
			mss_business_outlets.business_outlet_id
		ORDER BY
			mss_transactions.txn_datetime";
		
			$query = $this->db->query($sql);
			
			if($query){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			}
	}

		public function InsertRule($data,$table_name){
		// print_r($data);
		// exit;
		
		if($this->db->insert($table_name,$data)){
						$data = array('insert_id' => $this->db->insert_id());
						return $this->ModelHelper(true,false,'',$data);
		}
		else{
						return $this->ModelHelper(false,true,"Check your inserted query!");
		}
}
    //Checks whether rule is defined for the outlet
    public function CheckOutletRuleExists($where)
    {
    				$this->db->select('*');
    				$this->db->from('mss_loyalty_rules');
    				$this->db->where('business_outlet_id',$where['business_outlet_id']);
    				$this->db->where('rule_status',1);
    				$query = $this->db->get();
    				if($query->num_rows() == 0)
    				{
    								return $this->ModelHelper(false,true,"Rule does not Exists");
    				}
    				else if($query->num_rows() >= 1)
    				{
    								return $this->ModelHelper(true,false,'Rule Already Exists',$query->result_array());
    				}
    				
    }
    public function GetExistingRule($where)
    {
		$this->db->select('*');
		$this->db->from('mss_loyalty_rules');
		$this->db->where('business_outlet_id',$where);
		$this->db->where('rule_status',1);
		$sql = $this->db->get();
		if($sql->num_rows() > 0)
		{
				return $this->ModelHelper(true,false,'',$sql->result_array());
		}
		else
		{
				return $this->ModelHelper(false,true,'No rule exists');
		}
}

	//masterAdmin 
	//masterAdmin 
	public function GetBusinessAdminData($data){
		$sql = "SELECT
		mss_business_admin.*,
			mss_master_admin.master_admin_id,
			mss_master_admin.master_admin_name
	FROM
		mss_business_admin,
			mss_master_admin
	WHERE
		mss_business_admin.business_master_admin_id=mss_master_admin.master_admin_id AND
			mss_business_admin.business_admin_id=".$this->db->escape($data['business_admin_id'])." ";
	
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0){
				return $this->ModelHelper(true,false,'',$query->result_array());
			}
			else{
				return $this->ModelHelper(false,true,"DB error!");   
			}
	}
	
}

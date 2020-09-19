<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronModel extends CI_Model {

	//Generic function which will give all details by primary key of table

    public function getOutLetsAdmin(){
        $sql = "SELECT t1.business_admin_id,t2.business_outlet_id,t1.business_admin_email,t1.business_admin_first_name,t1.business_admin_last_name,t2.business_outlet_name FROM `mss_business_admin` t1 INNER JOIN mss_business_outlets t2 on t1.`business_admin_id` = t2.business_outlet_business_admin WHERE t2.business_outlet_status = 1 GROUP by t1.business_admin_email ORDER by t1.business_admin_id asc";
        $query = $this->db->query($sql);
        if($query && $query->num_rows() > 0){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"Your Outlet has been suspended.");   
        }
    }

    public function DetailsById($id,$date)
    {
        $sql='SELECT COUNT(id) as visit FROM `mss_transaction_cart` WHERE date(transaction_time) = "'.$date.'" and outlet_admin_id = "'.$id.'"
';            
        $query = $this->db->query($sql);
        if($query && $query->num_rows() > 0){
            return $this->ModelHelper(true,false,'',$query->row_array());
        }
        else{
            return $this->ModelHelper(false,true,"Your Outlet has been suspended.");   
        }  
    }    
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

        public function GetPaymentWiseReport($data){
        $sql = "SELECT 
                    date(mss_transactions.txn_datetime) AS 'Payment Date',          
                    -- mss_transaction_settlements.txn_settlement_way AS 'Settlement Way',
                    SUM(mss_transactions.txn_value) AS 'Total Amount'
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
                    AND date(mss_transactions.txn_datetime) = ".$this->db->escape($data['date']);

        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetPackageReport($data){
       $sql = "SELECT
                    -- mss_package_transactions.package_txn_unique_serial_id AS 'Serial Id',
                    -- mss_customers.customer_name AS 'Customer Name',
                    -- mss_customers.customer_mobile AS 'Customer Mobile',
                    -- date(mss_package_transactions.datetime) AS 'Purchase Date',
                    -- mss_salon_packages.salon_package_name AS 'Package Name',
                    -- mss_salon_packages.salon_package_type AS 'Package Type',
                    SUM(mss_package_transactions.package_txn_value) AS 'Bill Amount'
                    -- mss_package_transactions.package_txn_discount AS 'Discount Given',
                    -- mss_package_transactions.package_txn_pending_amount AS 'Pending Amount',
                    -- mss_package_transaction_settlements.settlement_way AS 'Settlement Way',
                    -- mss_package_transaction_settlements.payment_mode AS 'Payment Mode',
                    -- date_add(date(now()),INTERVAL mss_salon_packages.salon_package_validity MONTH) AS 'Expiry Date',
                    -- mss_employees.employee_first_name AS 'Expert Name'
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
                    AND date(mss_package_transactions.datetime) = ".$this->db->escape($data['date'])." 
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

     public function GetExpenseRecord($date,$outlet_id){

        // if(!empty($this->session->userdata['outlets']['current_outlet'])){
        //     $outlet_id = $this->session->userdata['outlets']['current_outlet'];
        // }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
        //     $outlet_id = $this->session->userdata['logged_in']['business_outlet_id'];
        // }        

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

    public function getOpeningRecord($date,$outlet_id){
        // if(!empty($this->session->userdata['outlets']['current_outlet'])){
        //     $outlet_id = $this->session->userdata['outlets']['current_outlet'];
        // }elseif(!empty($this->session->userdata['logged_in']['business_outlet_id'])){
        //     $outlet_id = $this->session->userdata['logged_in']['business_outlet_id'];
        // }
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
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronModel extends CI_Model {

	//Generic function which will give all details by primary key of table

    public function getOutLetsAdmin(){
        $sql = "SELECT t1.business_admin_id,t2.business_outlet_id,t1.business_admin_email,t1.business_admin_mobile,t1.business_admin_first_name,t1.business_admin_last_name,t2.business_outlet_name,t2.business_outlet_address,t2.business_outlet_state,t2.business_outlet_city,t2.business_outlet_country FROM `mss_business_admin` t1 INNER JOIN mss_business_outlets t2 on t1.`business_admin_id` = t2.business_outlet_business_admin WHERE t2.business_outlet_status = 1 GROUP by t1.business_admin_email ORDER by t1.business_admin_id asc";
        $query = $this->db->query($sql);
        if($query && $query->num_rows() > 0){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"Your Outlet has been suspended.");   
        }
    }

    public function DetailsById($id,$date,$enddate = 0)
    {
        if($enddate == 0){
            $sql='SELECT COUNT(id) as visit FROM `mss_transaction_cart` WHERE date(transaction_time) = "'.$date.'" and outlet_admin_id = "'.$id.'"
';          
        }else{
            $sql='SELECT COUNT(id) as visit FROM `mss_transaction_cart` WHERE date(transaction_time) between "'.$date.'" and  "'.$enddate.'" and outlet_admin_id = "'.$id.'"
';      
        }
              
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
        sum(mss_transaction_services.txn_service_discounted_price),mss_services.inventory_type_id

            FROM mss_transactions, mss_employees,mss_services,mss_transaction_services
            
            WHERE mss_transactions.txn_cashier=mss_employees.employee_id
            AND mss_transactions.txn_id= mss_transaction_services.txn_service_txn_id
            AND mss_transaction_services.txn_service_service_id= mss_services.service_id
            AND mss_employees.employee_business_outlet= ".$this->db->escape($data['business_outlet_id'])." 
            AND date(mss_transactions.txn_datetime) = ".$this->db->escape($data['date'])."
            AND mss_services.inventory_type_id in ('1')
            GROUP BY mss_services.inventory_type_id";
        $query = $this->db->query($sql);
        
        if($query){
            return $this->ModelHelper(true,false,'',$query->result_array());
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetPackageReport($data){       
        $sql = "SELECT SUM(mss_package_transactions.package_txn_value) AS 'Bill Amount' 
        
        FROM mss_package_transactions, mss_employees

            WHERE 
            
             mss_package_transactions.package_txn_expert= mss_employees.employee_id 
        
            AND mss_employees.employee_business_outlet  = ".$this->db->escape($data['business_outlet_id'])."
            
            AND mss_employees.employee_business_admin = ".$this->db->escape($data['business_admin_id'])."
        
            AND date(mss_package_transactions.datetime) = ".$this->db->escape($data['date'])."

            ORDER BY mss_package_transactions.package_txn_id";

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
            $sql = "select * from mss_opening_balance where opening_date='".$date."' and business_outlet_id = ".$outlet_id."  and amount_data is null";
        }else{
            $sql = "select * from mss_opening_balance where opening_date='".$date."' and amount_data is null";
        }        $query = $this->db->query($sql);
        $data['opening_balance'] = $query->result_array();
        if($data){
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function getAppointmentRecord(){
        $sql = 'SELECT t1.*, 
       t2.customer_name, 
       t2.customer_mobile, 
       t4.employee_first_name, 
       t4.employee_mobile, 
       t5.business_outlet_name, 
       t5.business_outlet_location, 
       t5.business_outlet_mobile ,
       t5.business_outlet_id
FROM   mss_appointments AS t1 
       inner join mss_customers AS t2 
               ON t1.customer_id = t2.customer_id 
       inner join mss_appointment_services t3 
               ON t1.appointment_id = t3.appointment_id 
       inner join mss_employees t4 
               ON t3.expert_id = t4.employee_id 
       inner join mss_business_outlets t5 
               ON t4.employee_business_outlet = t5.business_outlet_id 
WHERE  t1.appointment_date =  "'.date('Y-m-d').'"
       AND t1.appointment_status = 1
       AND Date_format( t1.`appointment_start_time` , "%h:%i") <= 
           Date_format(Now() + interval 60 minute, "%h:%i")';

// $sql = 'SELECT t1.*,t2.customer_name,t2.customer_mobile,t4.employee_first_name,t4.employee_mobile,t5.business_outlet_name,t5.business_outlet_address,t5.business_outlet_mobile  FROM mss_appointments as t1
// INNER JOIN mss_customers as t2 on t1.customer_id = t2.customer_id
// INNER JOIN mss_appointment_services t3 ON t1.appointment_id = t3.appointment_id
// INNER JOIN mss_employees t4 on t3.expert_id = t4.employee_id
// INNER JOIN mss_business_outlets t5 ON t4.employee_business_outlet = t5.business_outlet_id
// WHERE t1.appointment_date = "'.date('Y-m-d').'" and t1.appointment_status = 1';
        $query = $this->db->query($sql);
        $data['appointment_record'] = $query->result_array();
        if($data){
            return $this->ModelHelper(true,false,'',$data);
        }
        else{
            return $this->ModelHelper(false,true,"DB error!");   
        }
    }

    public function GetCashRecord($outlet_id,$from,$to){          

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

    public function GetTotalDueAmount($data){
        $sql = "SELECT X.EmpID,(Y.PDue+X.SDue) as total_due_amount FROM 

 (   
        SELECT sum(mss_transactions.txn_pending_amount) AS SDue,
     mss_employees.employee_id AS EmpID 
        
        FROM mss_transactions, mss_employees 
        WHERE date(mss_transactions.txn_datetime) BETWEEN  ".$this->db->escape($data['start_week'])." AND  ".$this->db->escape($data['end_week'])."
        AND mss_transactions.txn_cashier=mss_employees.employee_id
        AND mss_employees.employee_business_outlet=".$this->db->escape($data['business_outlet_id'])."
        group by mss_employees.employee_id
   ) as X
   
   JOIN
   
   ( 
       SELECT sum(mss_package_transactions.package_txn_pending_amount) as PDue, mss_employees.employee_id AS EmID
       
       FROM   mss_package_transactions, mss_employees
       
       WHERE
            mss_package_transactions.package_txn_cashier=mss_employees.employee_id
       AND date(mss_package_transactions.datetime) BETWEEN  ".$this->db->escape($data['start_week'])." AND  ".$this->db->escape($data['end_week'])." 
       AND mss_employees.employee_business_outlet = ".$this->db->escape($data['business_outlet_id'])."
       GROUP BY mss_employees.employee_id
      
      ) AS Y 
      
      on X.EmpID= Y.EmID
      group by X.EmpID";


            $query = $this->db->query($sql);
            
            if($query->num_rows()>0){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            } 
    }

    public function GetPendingAmountReceived($data){
        $sql = "SELECT SUM(mss_pending_amount_tracker.pending_amount_submitted) AS 'pending_amount_received' 
                FROM mss_pending_amount_tracker 
                    WHERE date(mss_pending_amount_tracker.date_time) between  ".$this->db->escape($data['start_week'])." AND  ".$this->db->escape($data['end_week'])."  
                    AND mss_pending_amount_tracker.business_outlet_id = ".$this->db->escape($data['business_outlet_id']);


            $query = $this->db->query($sql);
            
            if($query->num_rows()>0){
                return $this->ModelHelper(true,false,'',$query->result_array());
            }
            else{
                return $this->ModelHelper(false,true,"DB error!");   
            } 
    }
}
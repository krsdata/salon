<?php

    $con = mysqli_connect("localhost","tamarufa_root","Shubham#2019","tamarufa_marks_salon_solution");

  // Check connection
  if (mysqli_connect_errno())
     {
     $message= $con->connect_error;
    }

    $sql="SELECT 
			mss_employees.employee_mobile,
			mss_employees.employee_first_name As 'expert',
			mss_auto_engage.trigger_type,
			mss_business_outlets.business_outlet_sender_id,
			mss_business_outlets.api_key,
			COUNT(mss_transactions.txn_customer_id)'customers',
			COUNT(mss_transaction_services.txn_service_service_id)'services',
			SUM(mss_transaction_services.txn_service_discounted_price) AS 'sales'
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
			AND mss_business_outlets.business_outlet_sms_status=1
			AND mss_employees.employee_business_outlet=mss_business_outlets.business_outlet_id
			AND mss_auto_engage.business_outlet_id=mss_employees.employee_business_outlet
			AND mss_auto_engage.is_active=1
			AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
			AND mss_transaction_services.txn_service_service_id = mss_services.service_id              
			AND date(mss_transactions.txn_datetime)= DATE_SUB(CURDATE(), INTERVAL 1 DAY)
		GROUP BY mss_employees.employee_id,mss_auto_engage.trigger_type" ;

    $result = $con->query($sql);
    while($row=$result->fetch_assoc()){
			if($row['trigger_type']=='Daily_Update_Expert'){
        SendExpertSMS(8454801188,$row['expert'],date('d-m-Y',strtotime("-1 days")),$row['services'],$row['sales'],$row['business_outlet_sender_id'],$row['api_key']);
			}
      
    }
    //function calling
    //SendExpertSMS($mobile,$message);
    
    
    //
    function SendExpertSMS($mobile,$name,$date,$services,$sale,$sender_id,$api_key){
		//API key & sender ID
		// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
		// $apisender = "BILLIT";
		$msg = "Dear ".$name."\r\nSales Report\r\nDate :".$date."\r\nTotal services:". $services."\r\nTotal Sales : ".$sale;
           $msg = rawurlencode($msg);   //This for encode your message content   
        // $msg=$message;
			
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
	
	

?>

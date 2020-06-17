<?php 
    $con = mysqli_connect("localhost","tamarufa_root","Shubham#2019","tamarufa_marks_salon_solution");

  // Check connection
  if (mysqli_connect_errno())
     {
     $message= $con->connect_error;
    }else{
        $message="Connection Succcesful.";
    }
    $sql="SELECT 
			mss_business_admin.business_admin_mobile,
			mss_business_admin.business_admin_first_name,
			mss_business_outlets.business_outlet_name,
			mss_business_outlets.business_outlet_sender_id,
			mss_business_outlets.api_key,
			mss_business_outlets.business_outlet_location,
			mss_auto_engage.trigger_type,
			COUNT(DISTINCT mss_transactions.txn_customer_id)'Customers',
			COUNT(mss_transactions.txn_id)'visits',
			SUM(mss_transactions.txn_value) AS 'Total sales'
		FROM 
			mss_transactions,
			mss_transaction_settlements,
			mss_customers,
			mss_business_admin,
			mss_business_outlets,
			mss_auto_engage
		WHERE
			mss_transactions.txn_customer_id = mss_customers.customer_id AND
			mss_auto_engage.is_active=1 AND 
			mss_auto_engage.trigger_type='Daily_Update_Admin' AND
			mss_auto_engage.business_admin_id= mss_business_admin.business_admin_id AND
    		mss_auto_engage.business_outlet_id= mss_business_outlets.business_outlet_id AND
			mss_customers.customer_business_admin_id=mss_business_admin.business_admin_id AND
			mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id AND 
			mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id AND 
			date(mss_transactions.txn_datetime) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
		GROUP BY
			mss_business_outlets.business_outlet_id, mss_auto_engage.trigger_type" ;

    $result = $con->query($sql);
    while($row=$result->fetch_assoc()){
		if($row['trigger_type']){
			SendAutometicAppointmentSms(8454801188,$row['business_admin_first_name'],$row['business_outlet_location'],date('d-m-Y',strtotime('-1 days')),$row['Customers'],$row['visits'],$row['Total sales'],$row['business_outlet_name'],$row['business_outlet_sender_id'],$row['api_key']);
		}
      
    }
    
    //function calling
    //SendAutometicAppointmentSms($mobile,$message);
    
    //
    function SendAutometicAppointmentSms($mobile,$name,$location,$date,$customer,$visits,$sale,$outlet,$sender_id,$api_key){
		//API key & sender ID
// 		$apikey = "ll2C18W9s0qtY7jIac5UUQ";
// 		$apisender = "BILLIT";
		$msg = "Dear ".$name.",\r\nDaily Sales Report for ".$outlet.", ".$location."\r\nDate :".$date."\r\nSales(Rs.) :".$sale."\r\nVisits : ".$visits."\r\nUnique Customers: ".$customer."\r\nThanks.";
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

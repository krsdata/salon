<?php 
    $con = mysqli_connect("localhost","root","Server@db2019","marksalon");

  // Check connection
  if (mysqli_connect_errno())
     {
      $message= $con->connect_error;
    }else{
      $message="Connection Succcesful.";
    }
    $sql="SELECT
			mss_business_outlets.business_outlet_id,
			mss_business_outlets.business_outlet_sender_id,
			mss_business_outlets.api_key,
			mss_business_outlets.business_outlet_name,
			mss_business_outlets.business_outlet_location,
			mss_customers.customer_name,
			mss_customers.customer_mobile,
			mss_customers.customer_dob,
			mss_auto_engage.trigger_type,
			mss_auto_engage.trigger_days,
			mss_auto_engage.offer_type
		FROM
			mss_customers,
			mss_auto_engage,
			mss_business_outlets
		WHERE
			mss_business_outlets.business_outlet_status=1 AND
			mss_business_outlets.business_outlet_sms_status=1 AND
			mss_auto_engage.is_active=1 AND
			EXTRACT(MONTH FROM mss_customers.customer_dob)= EXTRACT(MONTH FROM CURRENT_DATE) AND
			EXTRACT(DAY FROM mss_customers.customer_dob) = EXTRACT(DAY FROM CURRENT_DATE) AND
			mss_auto_engage.business_admin_id= mss_business_outlets.business_outlet_business_admin AND
			mss_auto_engage.business_outlet_id=mss_business_outlets.business_outlet_id AND
			mss_customers.customer_business_admin_id= mss_business_outlets.business_outlet_business_admin AND
			mss_customers.customer_business_outlet_id= mss_business_outlets.business_outlet_id
     GROUP BY mss_business_outlets.business_outlet_id, mss_auto_engage.trigger_type" ;

		$result = $con->query($sql);
    while($row=$result->fetch_assoc()){
			if($row['trigger_type']=='Birthday'){
			SendAutometicAppointmentSms($row['customer_mobile'],$row['customer_name'],$row['business_outlet_location'],$row['business_outlet_name'],$row['business_outlet_sender_id'],$row['api_key'],$row['offer_type']);
			}    
    }
    
    //function calling
    //SendAutometicAppointmentSms($mobile,$message);    
    //
    function SendAutometicAppointmentSms($mobile,$name,$location,$outlet_name,$sender_id,$api_key,$offer){
		//API key & sender ID
		// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
		// $apisender = "BILLIT";
		$msg = "Happy Birthday Dear ".$name." FROM ".$outlet_name.",".$location.". GRAB flat ".$offer." off on your next visit."." Offer valid till ".date('Y-m-d', strtotime("+1 months", strtotime("NOW"))).".";
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

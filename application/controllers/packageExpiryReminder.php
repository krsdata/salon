<?php
   date_default_timezone_set("Asia/Kolkata"); 
 
    $con = mysqli_connect("localhost","tamarufa_root","Shubham#2019","tamarufa_marks_salon_solution");

  // Check connection
  if (mysqli_connect_errno())
     {
      $message= $con->connect_error;
    }else{
      $message="Connection Succcesful.";
    }
    $sql="SELECT mss_customers.customer_name,
    mss_customers.customer_mobile,
      mss_business_outlets.business_outlet_name,
      mss_business_outlets.business_outlet_location,
      mss_business_outlets.business_outlet_sender_id,
      mss_business_outlets.api_key,
      mss_customer_packages.package_expiry_date,
      mss_salon_packages.salon_package_name
  FROM
    mss_customers,
      mss_business_outlets,
      mss_customer_packages,
      mss_salon_packages
  WHERE
    mss_salon_packages.is_active=1 
  AND
    mss_customer_packages.customer_id=mss_customers.customer_id
  AND
    mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id
  AND
    mss_customer_packages.salon_package_id=mss_salon_packages.salon_package_id
  AND
    date(mss_customer_packages.package_expiry_date)=date(now())+ INTERVAL 1 DAY
  GROUP BY
    mss_customers.customer_id" ;

    $result = $con->query($sql);
   
    while($row=$result->fetch_assoc()){
     SendAutometicAppointmentSms($row['customer_mobile'],$row['customer_name'],$row['business_outlet_location'],$row['business_outlet_name'],$row['business_outlet_sender_id'],$row['api_key'],$row['package_expiry_date'],$row['salon_package_name']);
    //   echo $row['customer_name'].$row['customer_mobile'];
    }
    
    //function calling
    //SendAutometicAppointmentSms($mobile,$message);
    // echo "All the messages has been sent";
    
    //
    function SendAutometicAppointmentSms($mobile,$name,$location,$outlet_name,$sender_id,$api_key,$expiry_date,$package_name){
		//API key & sender ID
		// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
		// $apisender = "BILLIT";
		$msg = "Dear ".$name." your package ".$package_name." is been expired on ".$expiry_date.". Please visit ".$outlet_name." at ".$location." to redeem your services.";
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

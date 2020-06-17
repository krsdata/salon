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
    mss_customers.customer_name,
    mss_customers.customer_mobile,
      mss_business_outlets.business_outlet_name,
      mss_business_outlets.business_outlet_location,
      mss_business_outlets.business_outlet_sender_id,
      mss_business_outlets.api_key,
      mss_appointments.appointment_date,
      mss_appointments.appointment_start_time
  FROM
    mss_customers,
      mss_business_outlets,
      mss_appointments
  WHERE
    mss_appointments.appointment_status=1
  AND
    mss_appointments.customer_id=mss_customers.customer_id
  AND
    mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id
  AND
    mss_appointments.appointment_date=date(now())
  GROUP BY
    mss_customers.customer_id" ;

    $result = $con->query($sql);
  
    while($row=$result->fetch_assoc()){
     SendAutometicAppointmentSms($row['customer_mobile'],$row['customer_name'],$row['business_outlet_location'],$row['business_outlet_name'],$row['business_outlet_sender_id'],$row['api_key'],$row['appointment_date'],$row['appointment_start_time']);
    
    }
    
    //function calling
    //SendAutometicAppointmentSms($mobile,$message);    
    //
    function SendAutometicAppointmentSms($mobile,$name,$location,$outlet_name,$sender_id,$api_key,$appointment_date,$appointment_time){
		//API key & sender ID
		// $apikey = "ll2C18W9s0qtY7jIac5UUQ";
		// $apisender = "BILLIT";
		$msg = "Dear ".$name." you have an appointment at ".$outlet_name.",".$location." on ".$appointment_date." at ".substr($appointment_time,0,5).". Please visit to experience our best services.";
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

<?php 
    $con = mysqli_connect("localhost","tamarufa_root","Shubham#2019","tamarufa_marks_salon_solution");
    // $con = mysqli_connect("localhost","root","","marks_salon_solution");

  // Check connection
  	if (mysqli_connect_errno()){
      $message= $con->connect_error;
    }else{
			$message="Connection Succcesful.";
    }
    $sql="SELECT
		mss_transactions.txn_id,
		mss_transactions.txn_unique_serial_id,
		mss_transactions.txn_datetime,
		mss_business_outlets.business_outlet_name,
		mss_customers.customer_id,
		mss_customers.customer_mobile,
		mss_customers.customer_name,
		'Service',
		mss_transactions.txn_discount,
		mss_transactions.txn_value,
		mss_transactions.txn_cashier,
		mss_transactions.txn_total_tax,
		mss_transactions.txn_pending_amount,
		mss_transactions.txn_loyalty_points,
		mss_transactions.txn_status,
		mss_transactions.txn_remarks,
		mss_customers.customer_business_outlet_id,
		mss_customers.customer_business_admin_id,
		mss_business_admin.business_master_admin_id
		FROM 
			mss_transactions,
			mss_customers,
			mss_business_outlets,
			mss_business_admin
		where
		  mss_transactions.txn_customer_id=mss_customers.customer_id AND
			date(mss_transactions.txn_datetime)=CURRENT_DATE + INTERVAL -1 DAY AND 
			mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id	AND 
			mss_business_outlets.business_outlet_business_admin=mss_business_admin.business_admin_id" ;

		$result = $con->query($sql);
		// print_r($result);
		// exit;
    while($row=$result->fetch_assoc()){
			$txn_id=$row['txn_id'];
			$txn_unique_serial_id=$row['txn_unique_serial_id'];
			$txn_datetime=$row['txn_datetime'];
			$business_outlet_name=$row['business_outlet_name'];
			$customer_id=$row['customer_id'];
			$customer_mobile=$row['customer_mobile'];
			$customer_name=$row['customer_name'];
			$Service=$row['Service'];
			$txn_discount=$row['txn_discount'];
			$txn_value=$row['txn_value'];
			$txn_cashier=$row['txn_cashier'];
			$txn_total_tax=$row['txn_total_tax'];
			$txn_pending_amount=$row['txn_pending_amount'];
			$txn_loyalty_points=$row['txn_loyalty_points'];
			$txn_status=$row['txn_status'];
			$txn_remarks=$row['txn_remarks'];
			$business_outlet_id=$row['customer_business_outlet_id'];
			$business_admin_id=$row['customer_business_admin_id'];
			$business_master_admin_id=$row['business_master_admin_id'];
			
			$query="INSERT INTO mss_transactions_replica(txn_id,txn_unique_serial_id,txn_datetime,outlet_name,txn_customer_id,txn_customer_number,txn_customer_name,category_type,Quantity,txn_discount,txn_value,txn_cashier, CGST,SGST,txn_total_tax,txn_pending_amount,txn_loyalty_points_debited,txn_cashback_debited,txn_loyalty_points_balance,txn_status,txn_remarks,txn_outlet_id,txn_business_admin_id,master_admin_id) VALUES($txn_id,'$txn_unique_serial_id','$txn_datetime','$business_outlet_name',$customer_id,$customer_mobile,'$customer_name','$Service',1,$txn_discount,$txn_value,$txn_cashier,0,0,$txn_total_tax,$txn_pending_amount,$txn_loyalty_points,0,0,$txn_status,NULL,$business_outlet_id,$business_admin_id,$business_master_admin_id)";
			$res=$con->query($query);
// 			print_r($query);	
			// if(mysqli_query($con,$query)){
			// 	echo "record Inserted";
			// }else{
			// 	echo "Error";
			// }
			    
    }
    
    	
?>

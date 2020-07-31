<?php 
    $con = mysqli_connect("localhost","root","Server@db2019","marksalon");
    // $con = mysqli_connect("localhost","root","","marks_salon_solution");

  // Check connection
  	if (mysqli_connect_errno()){
      $message= $con->connect_error;
    }else{
			$message="Connection Succcesful.";
		}
		
    $sql="SELECT
		mss_transaction_services.txn_service_id,
		mss_transaction_services.txn_service_service_id,
		mss_transactions.txn_cashier,
		mss_transactions.txn_total_tax,
		mss_transaction_services.txn_service_expert_id,
		mss_transaction_services.txn_service_txn_id,
		mss_transactions.txn_datetime,
		mss_transactions.txn_pending_amount,
		mss_business_outlets.business_outlet_name,
		mss_customers.customer_id,
		mss_customers.customer_mobile,
		mss_customers.customer_name,
		mss_services.service_name,
		mss_services.service_type,
		mss_transaction_services.txn_service_quantity,
		mss_transactions.txn_unique_serial_id,
		mss_transaction_services.txn_service_discount_percentage,
		mss_transaction_services.txn_service_discount_absolute,
		mss_transaction_services.txn_service_discounted_price,
		mss_transaction_services.txn_service_status,
		mss_employees.employee_business_outlet,
		mss_employees.employee_first_name,
		mss_employees.employee_business_admin,
		mss_business_admin.business_master_admin_id
		FROM 
		mss_transaction_services,
		mss_employees,
		mss_business_outlets,
		mss_services,
		mss_transactions,
		mss_customers,
		mss_business_admin
		where 
			mss_transaction_services.txn_service_service_id=mss_services.service_id
		AND mss_transaction_services.txn_service_expert_id=mss_employees.employee_id
		AND mss_employees.employee_business_outlet=mss_business_outlets.business_outlet_id
		AND mss_transaction_services.txn_service_txn_id=mss_transactions.txn_id
		AND mss_transactions.txn_customer_id=mss_customers.customer_id AND
		date(mss_transactions.txn_datetime)=CURRENT_DATE 
		AND mss_business_admin.business_admin_id=mss_employees.employee_business_admin" ;

		$result = $con->query($sql);
		// print_r($result);
		// exit;
    while($row=$result->fetch_assoc()){
			// echo $row['employee_business_outlet'];
			// exit;
			$txn_service_id=$row['txn_service_id'];
			$txn_service_service_id=$row['txn_service_service_id'];
			$txn_cashier=$row['txn_cashier'];
			$txn_total_tax=$row['txn_total_tax'];
			$txn_service_expert_id=$row['txn_service_expert_id'];
			$expert_name=$row['employee_first_name'];
			$txn_service_txn_id=$row['txn_service_txn_id'];
			$txn_datetime=$row['txn_datetime'];
			$business_outlet_name=$row['business_outlet_name'];
			$customer_id=$row['customer_id'];
			$customer_mobile=$row['customer_mobile'];
			$customer_name=$row['customer_name'];
			$service_name=$row['service_name'];
			$service_type=$row['service_type'];
			$txn_service_quantity=$row['txn_service_quantity'];
			$txn_unique_serial_id=$row['txn_unique_serial_id'];
			$txn_service_discount_percentage=$row['txn_service_discount_percentage'];
			$txn_service_discount_absolute=$row['txn_service_discount_absolute'];
			$txn_service_discounted_price=$row['txn_service_discounted_price'];
			$txn_service_status=$row['txn_service_status'];
			$employee_business_outlet=$row['employee_business_outlet'];
			$employee_business_admin=$row['employee_business_admin'];
			$business_master_admin_id=$row['business_master_admin_id'];

			$query="INSERT INTO mss_transaction_services_replica (txn_service_id,txn_service_service_id, txn_service_expert_id,expert_name, txn_service_txn_id,txn_datetime, business_outlet_name, cust_id, cust_mobile, cust_name,service_name,service_type,txn_service_quantity, txn_service_discount_percentage, txn_service_discount_absolute, txn_service_discounted_price, CGST, SGST, other_tax, txn_service_status, business_outlet_id, business_admin_id, master_admin_id) VALUES ($txn_service_id, $txn_service_service_id, $txn_service_expert_id,'$expert_name',$txn_service_txn_id,'$txn_datetime', '$business_outlet_name', $customer_id, $customer_mobile, '$customer_name','$service_name', '$service_type', $txn_service_quantity, $txn_service_discount_percentage,$txn_service_discount_absolute, $txn_service_discounted_price, 0, 0, 0, $txn_service_status, $employee_business_outlet, $employee_business_admin, $business_master_admin_id)";


			$res=$con->query($query);
				// if(mysqli_query($con,$query)){
				// 	echo "record Inserted";
				// }else{
				// 	echo "Error";
				// }
			// print_r($query);  
    }
    
	
?>

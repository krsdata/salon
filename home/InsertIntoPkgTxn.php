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
		mss_package_transactions.package_txn_id,
		mss_package_transactions.package_txn_unique_serial_id,
    mss_package_transactions.datetime,
		mss_package_transactions.package_txn_expert,
		mss_business_outlets.business_outlet_name,
		mss_customers.customer_id,
		mss_customers.customer_mobile,
		mss_customers.customer_name,
		'Packages',
		mss_package_transactions.package_txn_discount,
		mss_package_transactions.package_txn_value,
		mss_package_transactions.package_txn_cashier,
		mss_package_transactions.package_txn_expert,
		mss_customers.customer_business_outlet_id,
		mss_customers.customer_business_admin_id,
		mss_business_admin.business_master_admin_id
		FROM mss_package_transactions,
		mss_customers,
		mss_business_outlets,
		mss_business_admin
		where
		mss_package_transactions.package_txn_customer_id=mss_customers.customer_id AND
		date(mss_package_transactions.datetime)=CURRENT_DATE AND 
		mss_customers.customer_business_outlet_id=mss_business_outlets.business_outlet_id
		AND mss_business_outlets.business_outlet_business_admin=mss_business_admin.business_admin_id" ;

		$result = $con->query($sql);
		// print_r($result);
		// exit;
    while($row=$result->fetch_assoc()){
			$package_txn_id=$row['package_txn_id'];
			$package_txn_unique_serial_id=$row['package_txn_unique_serial_id'];
			$datetime=$row['datetime'];
			$business_outlet_name=$row['business_outlet_name'];
			$customer_id=$row['customer_id'];
			$customer_mobile=$row['customer_mobile'];
			$customer_name=$row['customer_name'];
			$packages="Packages";
			$package_txn_discount=$row['package_txn_discount'];
			$package_txn_value=$row['package_txn_value'];
			$package_txn_cashier=$row['package_txn_cashier'];
			$package_txn_expert=$row['package_txn_expert'];
			$expert_name=$row['package_txn_expert'];
			$business_outlet_id=$row['customer_business_outlet_id'];
			$business_admin_id=$row['customer_business_admin_id'];
			$business_master_admin_id=$row['business_master_admin_id'];
			
			$query="INSERT INTO mss_package_transactions_replica(package_txn_id,package_txn_unique_serial_id,datetime,outlet_name,package_txn_customer_id,customer_number,customer_name,category_type,package_txn_discount,package_txn_value,package_txn_cashier,package_txn_expert,expert_name,CGST,SGST,total_tax,outlet_id,business_admin_id,master_admin_id) VALUES($package_txn_id,'$package_txn_unique_serial_id','$datetime','$business_outlet_name',$customer_id,$customer_mobile,'$customer_name','$packages',$package_txn_discount,$package_txn_value,$package_txn_cashier,$package_txn_expert,$expert_name,0,0,0,$business_outlet_id,$business_admin_id,$business_master_admin_id)";
			//$res=$con->query($query);
			echo $query;
			echo "<br>";
			// print_r($res);
			    
    }
    
    	
?>

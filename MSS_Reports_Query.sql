-----------------------------Reference Queries--------------------------

--Getting Revenue Date-Wise 
SELECT 
	date(mss_transactions.txn_datetime) AS txn_date,
	SUM(mss_transactions.txn_value) 
FROM 
	mss_transactions 
GROUP BY
 txn_date;

------------------------------------------------------------------------

-- Report 1 Date-wise sales report DONE
SELECT 
	date(mss_transactions.txn_datetime) AS BillingDate,
	SUM(mss_transaction_services.txn_service_discounted_price) AS TotalValue,
	mss_categories.category_name 
FROM 
	mss_categories,
	mss_sub_categories,
	mss_services,
    mss_transactions,
	mss_transaction_services 
WHERE
  mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
  AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
  AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
  AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
  AND mss_categories.category_business_admin_id = 1
  AND mss_categories.category_business_outlet_id = 1
  AND date(mss_transactions.txn_datetime) BETWEEN '2019-07-01' AND '2019-08-08'
GROUP BY 
	BillingDate, 
  mss_categories.category_id;

--Report2 Bill Wise Sales Report DONE
SELECT 
    mss_transactions.txn_id,date(mss_transactions.txn_datetime) AS BillingDate,
    mss_customers.customer_mobile,
    mss_customers.customer_name,
    mss_transactions.txn_value,
    mss_transaction_settlements.txn_settlement_way,
    mss_transaction_settlements.txn_settlement_payment_mode,
    mss_transaction_settlements.txn_settlement_amount_received,
    mss_transaction_settlements.txn_settlement_balance_paid 
FROM 
	mss_customers,
	mss_transactions,
	mss_transaction_settlements
WHERE 
	mss_transactions.txn_customer_id = mss_customers.customer_id
	AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
	AND mss_customers.customer_business_admin_id = 1
	AND mss_customers.customer_business_outlet_id = 1;

--Report 3 Employee Performance Report DONE
SELECT 
	mss_transactions.txn_id,
    mss_customers.customer_mobile,
    mss_customers.customer_name,
    date(mss_transactions.txn_datetime) AS BillingDate,
    mss_categories.category_name,
    mss_sub_categories.sub_category_name,
    mss_services.service_name,
    mss_employees.employee_first_name As 'Expert Name',
    mss_transaction_services.txn_service_discounted_price,
    mss_transactions.txn_value
FROM
	mss_customers,
    mss_categories,
    mss_sub_categories,
    mss_services,
    mss_transactions,
    mss_transaction_services,
    mss_employees
WHERE
	mss_transactions.txn_customer_id = mss_customers.customer_id
    AND mss_transactions.txn_id = mss_transaction_services.txn_service_service_id
    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    AND mss_categories.category_business_admin_id = 1
    AND mss_categories.category_business_outlet_id = 1 
    AND mss_customers.customer_business_admin_id = 1
    AND mss_customers.customer_business_outlet_id = 1
    AND mss_employees.employee_business_outlet = 1
    AND mss_employees.employee_business_admin = 1;

--Report4 Sub-Category Wise Sales Report DONE
SELECT 
	date(mss_transactions.txn_datetime) AS BillingDate, 
    mss_categories.category_name, 
    mss_sub_categories.sub_category_name,
    COUNT(mss_sub_categories.sub_category_id),
    SUM(mss_transaction_services.txn_service_discounted_price) AS TotalAmount 
FROM 
	mss_transactions, 
    mss_transaction_services, 
    mss_categories, 
    mss_sub_categories,
    mss_services
WHERE 
	mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    AND mss_categories.category_business_admin_id = 1 
    AND mss_categories.category_business_outlet_id = 1 
GROUP BY 
	BillingDate, 
    mss_categories.category_id,
    mss_sub_categories.sub_category_id;

--Report5 Item Wise Customer Report DONE
SELECT 
		mss_transactions.txn_id,
    date(mss_transactions.txn_datetime) AS BillingDate,
    mss_customers.customer_name,
    mss_customers.customer_mobile,
    mss_categories.category_name,
    mss_sub_categories.sub_category_name,
    mss_services.service_name,
    mss_transaction_services.txn_service_discounted_price
FROM 
	mss_transactions,
    mss_customers,
    mss_categories,
    mss_sub_categories,
    mss_services,
    mss_transaction_services
WHERE
	mss_transactions.txn_customer_id = mss_customers.customer_id
    AND mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    AND mss_categories.category_business_admin_id = 1
    AND mss_categories.category_business_outlet_id = 1
    AND mss_customers.customer_business_admin_id = 1
    AND mss_customers.customer_business_outlet_id = 1
ORDER BY mss_transactions.txn_id;
    
    
--Report6 Item Wise Sales Report DONE
SELECT 
	date(mss_transactions.txn_datetime) AS BillingDate,
    mss_categories.category_name,
    mss_sub_categories.sub_category_name,
    mss_services.service_name,
    COUNT(mss_services.service_id) AS BillCount,
   	SUM(mss_transaction_services.txn_service_discounted_price) AS Amount
FROM
	mss_transactions,
    mss_transaction_services,
    mss_categories,
    mss_sub_categories,
    mss_services
WHERE
	mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
    AND mss_transaction_services.txn_service_service_id = mss_services.service_id 
    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    AND mss_categories.category_business_admin_id = 1
    AND mss_categories.category_business_outlet_id = 1
GROUP BY
	BillingDate,
    mss_categories.category_id,
    mss_sub_categories.sub_category_id,
    mss_services.service_id


--Report7 Bill Wise Discount Report DONE
SELECT 
	mss_transactions.txn_id AS BillNo,
    mss_customers.customer_mobile,
    mss_customers.customer_name,
    date(mss_transactions.txn_datetime) AS BillingDate,
    mss_transactions.txn_value AS 'Actual Bill Paid',
    mss_transactions.txn_discount AS 'Discount Applied',
    mss_employees.employee_first_name,
    mss_employees.employee_last_name
FROM 
	mss_transactions,
    mss_transaction_services,
    mss_customers,
    mss_employees
WHERE
	mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
    AND mss_transactions.txn_customer_id = mss_customers.customer_id
    AND mss_transaction_services.txn_service_expert_id = mss_employees.employee_id
    AND mss_customers.customer_business_admin_id = 1
    AND mss_customers.customer_business_outlet_id = 1
    AND mss_employees.employee_business_outlet = 1
    AND mss_employees.employee_business_admin = 1
ORDER BY
	BillNo;

--Report8 Item Wise Tax Report --DOUBT

--Report 9 Inventory-Purchase -- Not Captured by us currently

--Report10 Inventory-Stock Report
	
	--OTC Report DONE
  SELECT 
		mss_services.service_name,
    mss_services.service_id AS ItemCode,
    mss_services.service_brand,
    mss_services.service_type,
	  mss_otc_stock.otc_sku AS CurrentStock
	FROM 
		mss_categories,
    mss_sub_categories,
    mss_services,
    mss_otc_stock 
	WHERE 
		mss_categories.category_id = mss_sub_categories.sub_category_category_id 
		AND mss_sub_categories.sub_category_id = mss_services.service_sub_category_id 
    AND mss_services.service_id = mss_otc_stock.otc_service_id 
    AND mss_categories.category_business_admin_id = 1 
    AND mss_services.service_is_active = 1 
    AND mss_categories.category_business_outlet_id = 1 
    AND mss_services.service_type = 'otc';

  --Raw Material Report DONE
  SELECT 
		mss_raw_material_categories.raw_material_name,
    mss_raw_material_categories.raw_material_unit,
    mss_raw_material_categories.raw_material_brand,
    mss_raw_material_categories.raw_material_type,
    mss_raw_material_stock.rm_entry_date,
    mss_raw_material_stock.rm_expiry_date,
    mss_raw_material_stock.rm_stock
	FROM 
		mss_raw_material_categories,
	  mss_raw_material_stock 
	WHERE 
			mss_raw_material_categories.raw_material_category_id = mss_raw_material_stock.rmc_id 
	    AND mss_raw_material_categories.raw_material_business_outlet_id = 1 
	    AND mss_raw_material_categories.raw_material_business_admin_id = 1 
	    AND mss_raw_material_categories.raw_material_is_active = 1;

--Report11 Payment Wise Report DONE
	SELECT 
    	date(mss_transactions.txn_datetime) AS PaymentDate, 		 
        mss_transaction_settlements.txn_settlement_way, 
        mss_transaction_settlements.txn_settlement_payment_mode,
        SUM(mss_transaction_settlements.txn_settlement_amount_received) AS Amount 
    FROM 
	   mss_transactions, 
       mss_transaction_settlements, 
       mss_customers 
    WHERE 
    	mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id 
        AND mss_transactions.txn_customer_id = mss_customers.customer_id 
        AND mss_customers.customer_business_admin_id = 1 
        AND mss_customers.customer_business_outlet_id = 1 
    GROUP BY 
    	PaymentDate,
        mss_transaction_settlements.txn_settlement_way, 
        mss_transaction_settlements.txn_settlement_payment_mode;

--Report12 Not Implemented

--Report13 Virtual Wallet Report DONE
SELECT
	mss_package_transactions.package_txn_id AS BillNo,
    mss_customers.customer_name,
    mss_customers.customer_mobile,
    date(mss_package_transactions.datetime) AS PurchaseDate,
    mss_salon_packages.salon_package_name,
    mss_salon_packages.salon_package_type,
    mss_customers.customer_virtual_wallet,
    mss_customers.customer_wallet_expiry_date
FROM
		mss_package_transactions,
    mss_customers,
    mss_salon_packages,
    mss_transaction_package_details
WHERE
	mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
    AND mss_package_transactions.package_txn_customer_id = mss_customers.customer_id
    AND mss_customers.customer_business_admin_id = 1
    AND mss_customers.customer_business_outlet_id = 1
    AND mss_salon_packages.business_admin_id = 1
    AND mss_salon_packages.business_outlet_id = 1;

--Report 14 Pending Amount Report DONE
	SELECT 
		mss_customers.customer_name,
    mss_customers.customer_mobile,
    mss_customers.customer_pending_amount
FROM
	mss_customers
WHERE
		mss_customers.customer_business_admin_id = 1
    AND mss_customers.customer_business_outlet_id = 1;


--Report15 DONE
	SELECT 
	date(mss_package_transactions.datetime) AS BillDate,
    mss_salon_packages.salon_package_name,
    mss_salon_packages.salon_package_type,
   	COUNT(mss_salon_packages.salon_package_id) AS PackageSoldCount,
    SUM(mss_package_transactions.package_txn_value) AS AmountCollected
FROM
	mss_package_transactions,
    mss_salon_packages,
    mss_transaction_package_details
WHERE
	mss_package_transactions.package_txn_id = mss_transaction_package_details.package_txn_id
    AND mss_transaction_package_details.salon_package_id = mss_salon_packages.salon_package_id
    AND mss_salon_packages.business_admin_id = 1
    AND mss_salon_packages.business_outlet_id = 1
GROUP BY
	BillDate,
    mss_salon_packages.salon_package_name,
    mss_salon_packages.salon_package_type;
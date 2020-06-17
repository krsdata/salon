--Query 1 DONE
	--Query 1.1 Sales Today
		SELECT 
			SUM(mss_transactions.txn_value) AS 'sales'
		FROM
			mss_transactions,
		  mss_customers
		WHERE
			mss_transactions.txn_customer_id = mss_customers.customer_id
			AND mss_customers.customer_business_admin_id = 1
			AND mss_customers.customer_business_outlet_id = 1
		  AND date(mss_transactions.txn_datetime) = '2019-07-05';
    

	--Query 1.2 Total Customer MTD
		SELECT 
			COUNT(mss_customers.customer_id) AS 'customer_count'
		FROM 
			mss_customers
		WHERE
			mss_customers.customer_business_admin_id = 1
		  AND mss_customers.customer_business_outlet_id = 1
		  AND date(mss_customers.customer_added_on) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now());
	

	--Query 1.3 New Customers MTD 
		SELECT 
			COUNT(*) 
		FROM
			(SELECT
				COUNT(*) as cnt
			FROM 
				mss_customers,
				mss_transactions
			WHERE 
				mss_transactions.txn_customer_id = mss_customers.customer_id
				AND mss_customers.customer_business_admin_id = 1
				AND mss_customers.customer_business_outlet_id = 1 
				AND date(mss_customers.customer_added_on) BETWEEN date(date_add(date('2019-08-31'),interval - DAY(date('2019-08-31'))+1 DAY)) AND date('2019-08-31')
			GROUP BY
				mss_transactions.txn_customer_id
			HAVING
				cnt = 1
			) T1; 

		--Existing Customer MTD
	  SELECT 
			COUNT(*) 
		FROM
			(SELECT
				COUNT(*) as cnt
			FROM 
				mss_customers,
				mss_transactions
			WHERE 
				mss_transactions.txn_customer_id = mss_customers.customer_id
				AND mss_customers.customer_business_admin_id = 1
				AND mss_customers.customer_business_outlet_id = 1 
				AND date(mss_customers.customer_added_on) BETWEEN date(date_add(date('2019-08-31'),interval - DAY(date('2019-08-31'))+1 DAY)) AND date('2019-08-31')
			GROUP BY
				mss_transactions.txn_customer_id
			HAVING
				cnt > 1
			) T1; 


	--Query 1.4 total_visits
		SELECT 
			COUNT(mss_transactions.txn_id) 
		FROM 
			mss_transactions, 
			mss_customers 
		WHERE 
			mss_transactions.txn_customer_id = mss_customers.customer_id 
			AND mss_customers.customer_business_admin_id = 1 
			AND mss_customers.customer_business_outlet_id = 1 
			AND date(mss_transactions.txn_datetime) BETWEEN date(date_add(date(now()),interval - DAY(date(now()))+1 DAY)) AND date(now())
	
	--Query 1.5 FTD Cash
		SELECT
			SUM(mss_transactions.txn_value) as 'ftd_cash'
		FROM 
		  	mss_transactions,
		  	mss_transaction_settlements,
		  	mss_customers
		WHERE
   			mss_transactions.txn_customer_id = mss_customers.customer_id
	    	AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id
	    	AND mss_transaction_settlements.txn_settlement_payment_mode = 'Cash'
	    	AND mss_customers.customer_business_admin_id = 1
	    	AND mss_customers.customer_business_outlet_id = 1
        AND date(mss_transactions.txn_datetime) = '2019-07-05'


		--Part of 1.5 need to be integrated (Split Payment)      
    SELECT 
    	mss_transaction_settlements.txn_settlement_payment_mode 
    FROM 
    	mss_transactions, 
    	mss_transaction_settlements, 
    	mss_customers 
    WHERE 
    	mss_transactions.txn_customer_id = mss_customers.customer_id 
    	AND mss_transactions.txn_id = mss_transaction_settlements.txn_settlement_txn_id 
    	AND mss_customers.customer_business_admin_id = 1 
    	AND mss_customers.customer_business_outlet_id = 1 
    	AND date(mss_transactions.txn_datetime) = '2019-08-25'

  --Query 1.6 Total Expenses
  SELECT 
	SUM(mss_expenses.amount) AS 'expense_today'
	FROM 
			mss_expenses,
	    mss_expense_types
	WHERE
			mss_expenses.expense_type_id = mss_expense_types.expense_type_id
	    AND mss_expense_types.expense_type_business_admin_id = 1
	    AND mss_expense_types.expense_type_business_outlet_id = 1
	    AND mss_expenses.expense_date = date('2019-08-02');

--Query 2 Low Stock Item [item_name,count] DONE
	SELECT
		mss_services.service_name,
	  mss_otc_stock.otc_sku
	FROM
		mss_otc_stock,
    mss_services,
    mss_sub_categories,
    mss_categories
	WHERE
		mss_otc_stock.otc_service_id = mss_services.service_id
    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
    AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
    AND mss_categories.category_business_admin_id = 1
    AND mss_categories.category_business_outlet_id = 1
    AND mss_services.service_type = 'otc'
    AND mss_otc_stock.otc_sku < 15;	

--Query 3 Gender Wise DONE
	SELECT 
		mss_customers.customer_title, 
	  COUNT(mss_customers.customer_id) AS count_gender
	FROM 
		mss_customers
	WHERE
		mss_customers.customer_business_admin_id = 1
	  AND mss_customers.customer_business_outlet_id = 1
	GROUP BY 
		mss_customers.customer_title;

--Query 4 Age Wise DONE
SELECT 
    t.age_group, 
    COUNT(*) AS age_count
FROM
(
    SELECT
        CASE WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 20 AND 25
             THEN '20-25'
             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 26 AND 35
             THEN '26-35'
             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 36 AND 45
             THEN '36-45'
             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) BETWEEN 46 AND 55
             THEN '46-55'
             WHEN TIMESTAMPDIFF(YEAR, mss_customers.customer_dob, CURDATE()) > 55
             THEN '46-55'
             ELSE 'Other'
        END AS age_group
    FROM 
        mss_customers
    WHERE 
        mss_customers.customer_business_admin_id = 1
        AND mss_customers.customer_business_outlet_id = 1
) t,mss_customers
GROUP BY t.age_group

--Query 5 Day Wise
	--5.1 Revenue DONE
		SELECT 
			date(mss_transactions.txn_datetime) as bill_date, 
			SUM(mss_transactions.txn_value) AS sales 
		FROM 
			mss_transactions,
			mss_customers 
		WHERE 
			mss_transactions.txn_customer_id = mss_customers.customer_id 
			AND date(mss_transactions.txn_datetime) BETWEEN '2019-07-05' AND '2019-07-12'
      AND mss_customers.customer_business_admin_id = 1
      AND mss_customers.customer_business_outlet_id = 1
		GROUP BY 
			date(mss_transactions.txn_datetime)
    ORDER BY
      date(mss_transactions.txn_datetime) DESC
    LIMIT 7;

	--5.2 Customer DONE
		SELECT 
		  date(mss_customers.customer_added_on) as add_date,
		  COUNT(mss_customers.customer_id) as total
		FROM 
			mss_customers
		WHERE
			mss_customers.customer_business_admin_id = 1
		  AND mss_customers.customer_business_outlet_id = 1
		  AND mss_customers.customer_added_on BETWEEN '2019-07-20' AND '2019-08-09'
		GROUP BY
			date(mss_customers.customer_added_on)
		ORDER BY
			date(mss_customers.customer_added_on) DESC
		LIMIT 7;

	--5.3 Visits DONE
		SELECT
			date(mss_transactions.txn_datetime) as visit_date,
			COUNT(mss_transactions.txn_id) AS 'total_visits'
		FROM 
			mss_transactions,
			mss_customers
		WHERE
			mss_transactions.txn_customer_id = mss_customers.customer_id
			AND mss_customers.customer_business_admin_id = 1
			AND mss_customers.customer_business_outlet_id = 1
		  AND date(mss_transactions.txn_datetime) BETWEEN '2019-07-01' AND '2019-07-30'
		GROUP BY
			date(mss_transactions.txn_datetime)
		ORDER BY
			date(mss_transactions.txn_datetime) DESC
		LIMIT 7;

--Query 6
	--Query 6.1.1 Service Wise Revenue Yearly
		SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    SUM(mss_transaction_services.txn_service_discounted_price) as revenue
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
        mss_sub_categories,
        mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
	      AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
	      AND mss_categories.category_business_admin_id = 1
        AND mss_categories.category_business_outlet_id = 1
		    AND mss_services.service_id = 1
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))

	--Query 6.1.2 Subcategory Wise Revenue Yearly
		SELECT 
	      YEAR(date(mss_transactions.txn_datetime)) AS year,
	      MONTHNAME(date(mss_transactions.txn_datetime)) as month,
	      SUM(mss_transaction_services.txn_service_discounted_price) as revenue
	  FROM 
	      mss_transactions,
	      mss_transaction_services,
	      mss_services,
	      mss_sub_categories,
        mss_categories
	  WHERE
	      mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
	      AND mss_transaction_services.txn_service_service_id = mss_services.service_id
	      AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
	      AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
	      AND mss_categories.category_business_admin_id = 1
        AND mss_categories.category_business_outlet_id = 1
	      AND mss_sub_categories.sub_category_id = 7 
	      AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
	  GROUP BY
	      YEAR(date(mss_transactions.txn_datetime)),
	      MONTH(date(mss_transactions.txn_datetime))


	--Query 6.1.3 Category Wise Revenue Yearly
		SELECT 
        YEAR(date(mss_transactions.txn_datetime)) AS year,
        MONTHNAME(date(mss_transactions.txn_datetime)) as month,
        SUM(mss_transaction_services.txn_service_discounted_price) as revenue
    FROM 
        mss_transactions,
        mss_transaction_services,
        mss_services,
        mss_sub_categories,
        mss_categories
    WHERE
        mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
        AND mss_transaction_services.txn_service_service_id = mss_services.service_id
        AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
        AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
        AND mss_categories.category_business_admin_id = 1
        AND mss_categories.category_business_outlet_id = 1
        AND mss_categories.category_id = 4 
        AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
    GROUP BY
        YEAR(date(mss_transactions.txn_datetime)),
        MONTH(date(mss_transactions.txn_datetime))

  --Query 6.2.1 Service Wise Visits
  	SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    COUNT(mss_transaction_services.txn_service_txn_id) as visits
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
        mss_sub_categories,
        mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
	      AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
	      AND mss_categories.category_business_admin_id = 1
        AND mss_categories.category_business_outlet_id = 1
		    AND mss_services.service_id = 1
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))

	--Query 6.2.2 Subcategory Wise Visits
				SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    COUNT(mss_transaction_services.txn_service_txn_id) as visits
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
      	mss_sub_categories,
      	mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
      	AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
      	AND mss_categories.category_business_admin_id = 1
      	AND mss_categories.category_business_outlet_id = 1
		    AND mss_sub_categories.sub_category_id = 7
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))

	--Query 6.2.3 Category Wise Visits
				SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    COUNT(mss_transaction_services.txn_service_txn_id) as visits
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
      	mss_sub_categories,
      	mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
      	AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
      	AND mss_categories.category_business_admin_id = 1
      	AND mss_categories.category_business_outlet_id = 1
		    AND mss_categories.category_id = 4
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))
 
 	--Query 6.3.1 DISTINCT Customer Monthly Basis Services
 		SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    COUNT(DISTINCT mss_transactions.txn_customer_id) as customer
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
        mss_sub_categories,
        mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
	    	AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
	    	AND mss_categories.category_business_admin_id = 1
	    	AND mss_categories.category_business_outlet_id = 1
		    AND mss_services.service_id = 1
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))

 --Query 6.3.2
 	SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    COUNT(DISTINCT mss_transactions.txn_customer_id) as customer
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
        mss_sub_categories,
        mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
	    	AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
	    	AND mss_categories.category_business_admin_id = 1
	    	AND mss_categories.category_business_outlet_id = 1
		    AND mss_sub_categories.sub_category_id = 7
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))
  
  --Query 6.3.3
  	SELECT 
		    YEAR(date(mss_transactions.txn_datetime)) AS year,
		    MONTHNAME(date(mss_transactions.txn_datetime)) as month,
		    COUNT(DISTINCT mss_transactions.txn_customer_id) as customer
		FROM 
		    mss_transactions,
		    mss_transaction_services,
		    mss_services,
        mss_sub_categories,
        mss_categories
		WHERE
		    mss_transactions.txn_id = mss_transaction_services.txn_service_txn_id
		    AND mss_transaction_services.txn_service_service_id = mss_services.service_id
		    AND mss_services.service_sub_category_id = mss_sub_categories.sub_category_id
	    	AND mss_sub_categories.sub_category_category_id = mss_categories.category_id
	    	AND mss_categories.category_business_admin_id = 1
	    	AND mss_categories.category_business_outlet_id = 1
		    AND mss_categories.category_id = 4
		    AND YEAR(date(mss_transactions.txn_datetime)) = YEAR(date(now()))
		GROUP BY
		    YEAR(date(mss_transactions.txn_datetime)),
		    MONTH(date(mss_transactions.txn_datetime))



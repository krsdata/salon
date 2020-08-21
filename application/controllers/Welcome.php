

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		// $this->load->view('welcome_message');
		redirect(base_url('home'), 'refresh');
	}

	
    public function cron_store_opening_balance(){        
		$this->load->model('BusinessAdminModel');
		if(!isset($_GET) || empty($_GET)){
		    if(!empty($_REQUEST['to_date'])){
		        $date = $_REQUEST['to_date'];
		        $one_day_before = date('Y-m-d', strtotime($date. ' - 1 days'));                    
		    }else{
		        $date = date('Y-m-d');    
		        $one_day_before = date('Y-m-d',strtotime("-1 days"));
		    }		 	
#			 $one_day_before = "2020-08-19";
		    $result = $this->BusinessAdminModel->GetCronExpenseRecord($one_day_before);
		}		

		if($result['success']){
		    $transaction = $result['res_arr']['transaction'];
		    $expenses = $result['res_arr']['expenses'];
		    $pending_amount = $result['res_arr']['pending_amount'];
		    $temp = [];
		    $transaction_data = [];
		    $json_data = [];                
		    //transaction data
		    $key_info = [];
		  	$i1 = 0;
		    foreach ($transaction as $key => $value) {
		        if(!empty($value['txn_settlement_payment_mode'])){                        
		            if(in_array(strtolower($value['txn_settlement_payment_mode']."##".$value['bussiness_outlet_id']), $temp)){
		                $transaction_data[strtolower($value['txn_settlement_payment_mode']."##".$value['bussiness_outlet_id'])] += $value['total_price'];
		            }else{
		                $result = json_decode($value['txn_settlement_payment_mode']);
		                if (json_last_error() === JSON_ERROR_NONE) {
		                	$txn_settlement_payment_mode = json_decode($value['txn_settlement_payment_mode'],true);	
		                	foreach ($txn_settlement_payment_mode as $key => $v1) {
		                		$txn_settlement_payment_mode[$key]['bussiness_outlet_id'] = $value['bussiness_outlet_id'];
		                	}
		                    $json_data[$i1] = $txn_settlement_payment_mode;
		                    $i1++;
		                }else{
		                    $transaction_data[strtolower($value['txn_settlement_payment_mode'])."##".$value['bussiness_outlet_id']] = $value['total_price'];
		                    $temp[] = strtolower($value['txn_settlement_payment_mode']."##".$value['bussiness_outlet_id']);
		                }		            
		            }
		        }                        
		    }
		    // echo "<pre>";
		    // print_r($json_data);
		    // die;
		    if(!empty($json_data)){                    
		        foreach ($json_data as $key => $j) {
		            foreach ($j as $key => $t) {
		              if(in_array(strtolower($t['payment_type']."##".$t['bussiness_outlet_id']), $temp)){
		                $transaction_data[strtolower($t['payment_type'])."##".$t['bussiness_outlet_id']] += $t['amount_received'];
		                }else{
		                    $transaction_data[strtolower($t['payment_type'])."##".$t['bussiness_outlet_id']] = $t['amount_received'];
		                    $temp[] = strtolower($t['payment_type']."##".$t['bussiness_outlet_id']);
		                }  
		            }                       
		        }
		    }
		    
		    $key_info['keys'][0] = $temp;                
		    // expenses

		    $temp = [];
		    $expenses_data = [];          
		    //transaction data           
		    foreach ($expenses as $key => $value) {
		        if(!empty($value['payment_mode'])){		            if(in_array(strtolower($value['payment_mode']."##".$value['bussiness_outlet_id']), $temp)){
		                $expenses_data[strtolower($value['payment_mode'])."##".$value['bussiness_outlet_id']] += $value['total_amount'];
		            }else{                            
		                    $expenses_data[strtolower($value['payment_mode'])."##".$value['bussiness_outlet_id']] = $value['total_amount'];
		                    $temp[] = strtolower($value['payment_mode']."##".$value['bussiness_outlet_id']);
		            }
		        }                        
		    }
		    $key_info['keys'][1] = $temp;
		     
		    // pending tracker
		    $temp = [];
		    $pending_amount_data = [];          
		    //transaction data        echo "<pre>";
		

		    foreach ($pending_amount as $key => $value) {
		        if(!empty($value['payment_type'])){                        
		            if(in_array(strtolower($value['payment_type']."##".$value['bussiness_outlet_id']), $temp)){
		                $pending_amount_data[strtolower($value['payment_type']."##".$value['bussiness_outlet_id'])] += $value['total_amount'];
		            }else{                            
		                    $pending_amount_data[strtolower($value['payment_type']."##".$value['bussiness_outlet_id'])] = $value['total_amount'];
		                    $temp[] = strtolower($value['payment_type']."##".$value['bussiness_outlet_id']);
		            }
		        }                        
		    }
		    $key_info['keys'][2] = $temp;	

		    //get opening record		
		    $one_day_before1 = date('Y-m-d', strtotime($one_day_before. ' - 1 days'));
			$result = $this->BusinessAdminModel->getOpeningRecord($one_day_before1);
#echo $this->db->last_query();die;
			$opening_balance = $result['res_arr']['opening_balance'];		
			$temp = [];
		    $opening_balance_data = [];          
		    //transaction data                        
		    foreach ($opening_balance as $key => $value) {
		        if(!empty($value['payment_mode'])){                        
		            if(in_array(strtolower($value['payment_mode']."##".$value['business_outlet_id']), $temp)){
		                $opening_balance_data[strtolower($value['payment_mode'])."##".$value['business_outlet_id']] += $value['amount'];
		            }else{                            
		                    $opening_balance_data[strtolower($value['payment_mode'])."##".$value['business_outlet_id']] = $value['amount'];
		                    $temp[] = strtolower($value['payment_mode']."##".$value['business_outlet_id']);
		            }
		        }                        
		    }
		    $key_info['keys'][3] = $temp;



		    $p_mode = [];
		    foreach ($key_info as $key => $k) {
		        foreach ($k as $key => $keys) {
		            if(!in_array($keys, $p_mode)){
		                $p_mode[] = $keys;
		            }
		        }                                    
		    }

		}
		//  
		$p_mode = call_user_func_array('array_merge', $p_mode);
		$p_mode = array_unique($p_mode);		
		$data['p_mode'] = $p_mode;
		$data['pending_amount_data'] = $pending_amount_data;
		$data['expenses_data'] = $expenses_data;
		$data['transaction_data'] = $transaction_data;
		$data['date'] = $date;
echo "<pre>";
#print_r($data);die;
		$total_t = [];
		$total_p = [];
		$total_e = [];
		$payment = [];
		$i = 0;
		$temp = [];
		// foreach ($p_mode as $key => $p) {
		// 	$p1 = explode("##", $p);

		// 	$payment[$i]['payment_mode'] = $p1[0];
		// 	$payment[$i]['amount'] = (!empty($transaction_data[$p])?$transaction_data[$p]:0)+(!empty($pending_amount_data[$p])?$pending_amount_data[$p]:0)-(!empty($expenses_data[$p])?$expenses_data[$p]:0);
		// 	$payment[$i]['business_outlet_id'] = $p1[1];	
		// 	$payment[$i]['opening_date'] = $one_day_before;
		// 	$i++;
		// }

		foreach ($p_mode as $key => $p) {
			$p1 = explode("##", $p);
			// if($p1[0] == "virtual_wallet"){
			// 	$total_o[$p1[0]][$p1[1]] = [$p1[0]][$p1[1]]-$opening_balance_data[$p];
			// 	$total_t[$p1[0]][$p1[1]] = $total_t[$p1[0]][$p1[1]]-$transaction_data[$p];
			// 	$total_p[$p1[0]][$p1[1]] = $total_p[$p1[0]][$p1[1]]-$pending_amount_data[$p];
			// 	$total_e[$p1[0]][$p1[1]] = $total_e[$p1[0]][$p1[1]]-$expenses_data[$p];
			// }else{
			// 	$total_o[$p1[0]][$p1[1]] = $total_o[$p1[0]][$p1[1]]+$opening_balance_data[$p];
			// 	$total_t[$p1[0]][$p1[1]] = $total_t[$p1[0]][$p1[1]]+$transaction_data[$p];
			// 	$total_p[$p1[0]][$p1[1]] = $total_p[$p1[0]][$p1[1]]+$pending_amount_data[$p];
			// 	$total_e[$p1[0]][$p1[1]] = $total_e[$p1[0]][$p1[1]]+$expenses_data[$p];
			// }	
			$total_o[$p1[0]][$p1[1]] = abs($opening_balance_data[$p]+$transaction_data[$p]+$pending_amount_data[$p]-$expenses_data[$p]);
		}
		echo "<pre>";
#print_r($total_o);
#die;
		foreach ($total_o as $key => $t) {
			//$i = 0;
			foreach ($t as $k => $v) {
				$payment[$i]['payment_mode'] = $key;
				$payment[$i]['amount'] = $v;
				$payment[$i]['business_outlet_id'] = $k;
				$payment[$i]['opening_date'] = $one_day_before;
				$i++;
			}			
		}
#		print_r($payment);die;		
#		die;
		if(!empty($payment))
		$this->db->insert_batch('mss_opening_balance', $payment); 		
    }
}

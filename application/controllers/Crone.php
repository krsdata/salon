

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crone extends CI_Controller {

	public function index()
	{
		// $this->load->view('welcome_message');
		redirect(base_url('home'), 'refresh');
	}

	function sendDailyReport(){
		$this->load->model('CronModel');
		//$this->load->model('BusinessAdminModel');

		$outlets = $this->CronModel->getOutLetsAdmin();
		if(empty($outlets))
			return true;

		foreach ($outlets['res_arr'] as $key => $ol) {
			$detail = $this->CronModel->DetailsById($ol['business_admin_id'],'2020-09-08');
			$data['visit'] = 0;
			if($detail['success'])
				$data['visit'] = $detail['res_arr']['visit'];

			$where = array('business_outlet_id'=>$ol['business_outlet_id'],'business_admin_id'=>$ol['business_admin_id'],'date'=>'2020-09-08');
			$result = $this->CronModel->GetPaymentWiseReport($where);		
			$service_Amt = 0;
			if($result['success']){
				$service_Amt = $result['res_arr'][0]['Total Amount'];
			}

			$result = $this->CronModel->GetPackageReport($where);
			//echo $this->db->last_query();
			$package_Amt = 0;
			if($result['success']){
				$package_Amt = $result['res_arr'][0]['Bill Amount'];
			}		
			
			$collection = $this->daybook('2020-07-16',$ol['business_outlet_id']);
			
			$data['collection'] = $collection;
			$data['service_Amt'] = $service_Amt;
			$data['package_Amt'] = $package_Amt;
			$data['total_sp_Amt'] = $package_Amt+$service_Amt;
			$data['package_Amt'] = $package_Amt;
			$data['admin_name'] = $ol['business_admin_first_name']." ".$ol['business_admin_last_name'];
			$data['business_outlet_name'] = $ol['business_outlet_name'];
            $data['business_outlet_address'] = $ol['business_outlet_address'];
            $data['business_outlet_state'] = $ol['business_outlet_state'];
            $data['business_outlet_city'] = $ol['business_outlet_city'];
            $data['business_outlet_country'] = $ol['business_outlet_country'];


			// email send

			 $config = Array(    

				      'protocol' => 'smtp',

				      'smtp_host' => 'ssl://smtp.gmail.com',

				      'smtp_port' => 465,

				      'smtp_user' => 'test.developer124@gmail.com',

				      'smtp_pass' => 'codemeg@1234',

				      'smtp_timeout' => '4',

				      'mailtype' => 'html',

				      'charset' => 'iso-8859-1'

				    );

				    $this->load->library('email', $config);

				  $this->email->set_newline("\r\n");

				  

				    $this->email->from('test.developer124@gmail.com', 'Daily collection');

				  

				    $this->email->to('ansari.ismael90@gmail.com'); // replace it with receiver mail id

				  $this->email->subject('Daily collection Report'); // replace it with relevant subject

				  // echo "<pre>";
				  // print_r($data);
				  // die;

				    $body = $this->load->view('kpi',$data,TRUE);

				  $this->email->message($body); 

				    $this->email->send();


die;
			// echo "<pre>";
			// print_r($data);
			// die;
		}

		//$this->load->view('kpi',$data);
	}

	public function daybook($date,$outlet_id){        			
        $this->load->model('CronModel');
        $one_day_before = date($date,strtotime("-1 days"));
        $result = $this->CronModel->GetExpenseRecord($date,$outlet_id); 

        if($result['success']){
            $transaction = $result['res_arr']['transaction'];
            $expenses = $result['res_arr']['expenses'];
            $pending_amount = $result['res_arr']['pending_amount'];
            $temp = [];
            $transaction_data = [];
            $json_data = [];                
            //transaction data
            $key_info = [];
            
            foreach ($transaction as $key => $value) {
                if(!empty($value['txn_settlement_payment_mode'])){                        
                    if(in_array(strtolower($value['txn_settlement_payment_mode']), $temp)){
                        $transaction_data[strtolower($value['txn_settlement_payment_mode'])] += $value['total_price'];
                    }else{
                        $result = json_decode($value['txn_settlement_payment_mode']);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $json_data[] = json_decode($value['txn_settlement_payment_mode'],true);
                        }else{
                            $transaction_data[strtolower($value['txn_settlement_payment_mode'])] = $value['total_price'];
                            $temp[] = strtolower($value['txn_settlement_payment_mode']);
                        }                            
                    }
                }                        
            }                
            if(!empty($json_data)){                    
                foreach ($json_data as $key => $j) {
                    foreach ($j as $key => $t) {
                      if(in_array(strtolower($t['payment_type']), $temp)){
                        $transaction_data[strtolower($t['payment_type'])] += $t['amount_received'];
                        }else{
                            $transaction_data[strtolower($t['payment_type'])] = $t['amount_received'];
                            $temp[] = strtolower($t['payment_type']);
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
                if(!empty($value['payment_mode'])){                        
                    if(in_array(strtolower($value['payment_mode']), $temp)){
                        $expenses_data[strtolower($value['payment_mode'])] += $value['total_amount'];
                    }else{                            
                            $expenses_data[strtolower($value['payment_mode'])] = $value['total_amount'];
                            $temp[] = strtolower($value['payment_mode']);
                    }
                }                        
            }
            $key_info['keys'][1] = $temp;
            // pending tracker
            $temp = [];
            $pending_amount_data = [];          
            //transaction data                        
            foreach ($pending_amount as $key => $value) {
                if(!empty($value['payment_type'])){                        
                    if(in_array(strtolower($value['payment_type']), $temp)){
                        $pending_amount_data[strtolower($value['payment_type'])] += $value['total_amount'];
                    }else{                            
                            $pending_amount_data[strtolower($value['payment_type'])] = $value['total_amount'];
                            $temp[] = strtolower($value['payment_type']);
                    }
                }                        
            }
            $key_info['keys'][2] = $temp;

            //get opening record        
            $result = $this->CronModel->getOpeningRecord($one_day_before,$outlet_id);
            $opening_balance = $result['res_arr']['opening_balance'];       
            $temp = [];
                $opening_balance_data = [];          
                //transaction data                        
                foreach ($opening_balance as $key => $value) {
                    if(!empty($value['payment_mode'])){                        
                        if(in_array(strtolower($value['payment_mode']), $temp)){
                            $opening_balance_data[strtolower($value['payment_mode'])] += $value['amount'];
                        }else{                            
                                $opening_balance_data[strtolower($value['payment_mode'])] = $value['amount'];
                                $temp[] = strtolower($value['payment_mode']);
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
        
        $p_mode = array_filter($p_mode);        
        $p_mode = call_user_func_array('array_merge', $p_mode);
        $p_mode = array_unique($p_mode);
        $p_mode = array_values($p_mode);        
        $data['p_mode'] = $p_mode;
        $data['opening_balance_data'] = $opening_balance_data;
        $data['pending_amount_data'] = $pending_amount_data;
        $data['expenses_data'] = $expenses_data;
        $data['transaction_data'] = $transaction_data;
        $data['date'] = $date;
        return $data;        
        //$this->load->view('cashier/cashier_book_view',$data);
    }



}

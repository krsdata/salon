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

			$detail = $this->CronModel->DetailsById($ol['business_admin_id'],date('Y-m-d'));
			$data['visit'] = 0;
			if($detail['success'])
				$data['visit'] = $detail['res_arr']['visit'];

			$where = array('business_outlet_id'=>$ol['business_outlet_id'],'business_admin_id'=>$ol['business_admin_id'],'date'=>date('Y-m-d'));
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
			
			$collection = $this->daybook(date('Y-m-d'),$ol['business_outlet_id']);
			
			$data['collection'] = $collection;
			$data['service_Amt'] = $service_Amt;
			$data['package_Amt'] = $package_Amt;
			$data['total_sp_Amt'] = $package_Amt+$service_Amt;
			$data['package_Amt'] = $package_Amt;
			$data['admin_name'] = $ol['business_admin_first_name']." ".$ol['business_admin_last_name'];
			$data['business_outlet_name'] = $ol['business_outlet_name'];
            $data['business_outlet_location'] = $ol['business_outlet_location'];
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

    public function sendSMSBeforeAppointment(){
        $now = date("H");
        // if ($now < "10" || $now > "22") {
        //     die();
        // }
        $this->load->model('CronModel');
        $this->load->model('BusinessAdminModel');
        $result = $this->CronModel->getAppointmentRecord();
        #echo $this->db->last_query();
        
        if($result['success']){
            $record = $result['res_arr']['appointment_record'];

            foreach ($record as $key => $r) {

                $activity =$this->BusinessAdminModel->GetOutLetSMSActivity([$r['business_outlet_id']]);

                $ac = [];
                if($activity['success']){
                    $activity = $activity['res_arr'];                        
                    foreach ($activity as $key => $a) {
                        $ac[] = $a['outlet_id']."_".$a['services_number'];
                    }
                }

                if(!in_array($r['business_outlet_id']."_1", $ac)){               
                    continue;
                }


                $exp_message = "Dear ".$r['employee_first_name'].",You've an upcoming service with ".$r['customer_name'].", ".$r['customer_mobile']." in 30mins. Please be ready to serve the patron with your best expertise."; 
                
                $this->sendMessage($r['employee_mobile'],$exp_message);

                $customer_message = "Dear ".$r['customer_name'].",You've an upcoming appointment with ".$r['business_outlet_name']." in 30mins. Look forward to ur patronage. Team ".$r['business_outlet_name']." ".$r['business_outlet_mobile']." ".$r['business_outlet_location'];
                $this->sendMessage($r['customer_mobile'],$customer_message);
                #$this->sendWatsupMessage($r['customer_mobile'],$customer_message);                
            }
           
        die;
        }
    }

    function sendMessage($mobile,$message){        
        $api_key = "4XA1l9jcXkChf9TLKcI9bw";
        $sender_id = "SLNFST";        
         $msg = rawurlencode($message); //This for encode your message content
         $mobile = '7000710898';
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

    function sendWatsupMessage($mobile,$message){  
    die('twt');
        $authKey = "JH3E76DHNYeIcwD";
        $clientId = "0rfMvmvjSxODwIp";        
        $userId = "9";
         $msg = rawurlencode($message); //This for encode your message content
         $mobile = '7000710898';
         // API
        $url = 'http://api.mobileadz.in/api/message/send?data={"textMessage":"'.$msg.'","toAddress":"'.$mobile.'","userId":"'.$userId.'","clientId":"'.$clientId.'","authKey":"'.$authKey.'"}';
                                    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        
        $data = curl_exec($ch);
        return json_encode($data);
    }

    public function dayClosingReport(){
        $this->load->model('CronModel');
        $this->load->model('BusinessAdminModel');
        $outlets = $this->CronModel->getOutLetsAdmin();                    
        if(empty($outlets))
            return true;
        $date = date('Y-m-d');
        foreach ($outlets['res_arr'] as $key => $ol) {                        
            $activity =$this->BusinessAdminModel->GetOutLetSMSActivity([$ol['business_outlet_id']]);

            $ac = [];
            if($activity['success']){
                $activity = $activity['res_arr'];                        
                foreach ($activity as $key => $a) {
                    $ac[] = $a['outlet_id']."_".$a['services_number'];
                }
            }

            if(!in_array($ol['business_outlet_id']."_2", $ac)){               
                continue;
            }
            

            $detail = $this->CronModel->DetailsById($ol['business_admin_id'],$date);      

            $data['visit'] = 0;
            if($detail['success'])
                $data['visit'] = $detail['res_arr']['visit'];

            $where = array('business_outlet_id'=>$ol['business_outlet_id'],'business_admin_id'=>$ol['business_admin_id'],'date'=>$date);
            $result = $this->CronModel->GetPaymentWiseReport($where);       

            $service_Amt = 0;
            if($result['success']){
                $service_Amt = $result['res_arr'][0]['Total Amount'];
            }

            $result = $this->CronModel->GetPackageReport($where);
            
            $package_Amt = 0;
            if($result['success']){
                $package_Amt = $result['res_arr'][0]['Bill Amount'];
            }       
            
            $collection = $this->daybook($date,$ol['business_outlet_id']);

            $data['collection'] = $collection;
            $data['service_Amt'] = $service_Amt;
            $data['package_Amt'] = $package_Amt;
            $data['total_sp_Amt'] = $package_Amt+$service_Amt;
            $data['package_Amt'] = $package_Amt;
            $data['admin_name'] = $ol['business_admin_first_name']." ".$ol['business_admin_last_name'];
            $data['business_outlet_name'] = $ol['business_outlet_name'];
            $data['business_outlet_location'] = $ol['business_outlet_location'];
            $data['business_outlet_state'] = $ol['business_outlet_state'];
            $data['business_outlet_city'] = $ol['business_outlet_city'];
            $data['business_outlet_country'] = $ol['business_outlet_country'];

            $total_o = 0;
            $total_t = 0;
            $total_p = 0;
            $total_e = 0;
            foreach ($collection['p_mode'] as $key => $p) {
                $collection['opening_balance_data'][$p];
                if($p == "virtual_wallet"){
                    $total_o = $total_o-$collection['opening_balance_data'][$p];
                }else{
                    $total_o = $total_o+$collection['opening_balance_data'][$p];
                }                                               
                $collection['transaction_data'][$p];
                if($p == "virtual_wallet"){
                    $total_t = $total_t-$collection['transaction_data'][$p];
                }else{
                    $total_t = $total_t+$collection['transaction_data'][$p];
                }                                               
                $collection['pending_amount_data'][$p];
                if($p == "virtual_wallet"){
                    $total_p = $total_p-$collection['pending_amount_data'][$p];
                }else{
                    $total_p = $total_p+$collection['pending_amount_data'][$p];
                }
                $collection['expenses_data'][$p];
                if($p == "virtual_wallet"){
                    $total_e = $total_e-$collection['expenses_data'][$p];
                }else{
                    $total_e = $total_e+$collection['expenses_data'][$p];
                }
            }
#echo "<pre>";print_r($total_t);die;
            // $where = array('business_outlet_id'=>$ol['business_outlet_id'],'business_admin_id'=>$ol['business_admin_id'],'date'=>$date);
            // $result = $this->CronModel->GetPaymentWiseReport($where);       
            // $service_Amt = '0';
            // if($result['success']){
            //     $service_Amt = !empty($result['res_arr'][0]['Total Amount'])?$result['res_arr'][0]['Total Amount']:0;
            // }            

            $msg = "Hi ".$ol['business_outlet_name'].", ".$ol['business_outlet_location']." ! Business Update till 10pm
            Sales: Rs.$service_Amt, Collection: Rs.$total_t, Expenses : Rs.$total_e, Due Amt : Rs.$total_p          Visits:".$data['visit'];

            //echo $msg."<br><br>";
            $this->sendMessage($ol['business_admin_mobile'],$msg);            
        }
        die;
    }

    public function generateReportSMS($duration){
        $this->load->model('CronModel');
        $this->load->model('BusinessAdminModel');
        if($duration == "w"){
            $previous_week = strtotime("-1 week +1 day");

            $start_week = strtotime("last monday midnight",$previous_week);
            $end_week = strtotime("next sunday",$start_week);

            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);

            #echo $start_week.' '.$end_week ;            
        }elseif($duration == "m"){
            $start_week = date('Y-m-01',strtotime('last month'));
            $end_week = date('Y-m-t',strtotime('last month'));
        }

        $outlets = $this->CronModel->getOutLetsAdmin();
        if(empty($outlets))
            return true;
        foreach ($outlets['res_arr'] as $key => $ol) { 

            $activity =$this->BusinessAdminModel->GetOutLetSMSActivity([$ol['business_outlet_id']]);

            $ac = [];
            if($activity['success']){
                $activity = $activity['res_arr'];                        
                foreach ($activity as $key => $a) {
                    $ac[] = $a['outlet_id']."_".$a['services_number'];
                }
            }

            if($duration == 'w' && !in_array($ol['business_outlet_id']."_3", $ac)){               
                continue;
            }

            if($duration == 'm' && !in_array($ol['business_outlet_id']."_5", $ac)){
                continue;
            }



            $result = $this->CronModel->GetCashRecord($ol['business_outlet_id'],$start_week,$end_week);

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
        $collection['p_mode'] = $p_mode;
        $collection['opening_balance_data'] = $opening_balance_data;
        $collection['pending_amount_data'] = $pending_amount_data;
        $collection['expenses_data'] = $expenses_data;
        $collection['transaction_data'] = $transaction_data;
        $total_o = 0;
        $total_t = 0;
        $total_p = 0;
        $total_e = 0;
        foreach ($collection['p_mode'] as $key => $p) {
            $collection['opening_balance_data'][$p];
                if($p == "virtual_wallet"){
                    $total_o = $total_o-$collection['opening_balance_data'][$p];
                }else{
                    $total_o = $total_o+$collection['opening_balance_data'][$p];
                }                                               
                $collection['transaction_data'][$p];
                if($p == "virtual_wallet"){
                    $total_t = $total_t-$collection['transaction_data'][$p];
                }else{
                    $total_t = $total_t+$collection['transaction_data'][$p];
                }                                               
                $collection['pending_amount_data'][$p];
                if($p == "virtual_wallet"){
                    $total_p = $total_p-$collection['pending_amount_data'][$p];
                }else{
                    $total_p = $total_p+$collection['pending_amount_data'][$p];
                }                                               
                $collection['expenses_data'][$p];
                if($p == "virtual_wallet"){
                    $total_e = $total_e-$collection['expenses_data'][$p];
                }else{
                    $total_e = $total_e+$collection['expenses_data'][$p];
                }
            }

            $detail = $this->CronModel->DetailsById($ol['business_admin_id'],$start_week,$end_week);
            $visit = 0;
            if($detail['success'])
                $visit = $detail['res_arr']['visit'];

            $msg = "Hi ".$ol['business_outlet_name'].", ".$ol['business_outlet_location']."!
            Business Update: From $start_week - To $end_week
            Sales: Rs.$total_t
            Collection: Rs.$total_t
            Expenses : Rs.$total_e
            Due Amt : Rs.$total_p
            Visits: $visit";
           $this->sendMessage($ol['business_admin_mobile'],$msg);
           //  echo $msg;
            // echo "<br><br>";
            //die;

        }
        die;
    }

    public function pendingAmountSMS($duration){
        $this->load->model('CronModel');
        $this->load->model('BusinessAdminModel');
        if($duration == "w"){
            $previous_week = strtotime("-1 week +1 day");

            $start_week = strtotime("last monday midnight",$previous_week);
            $end_week = strtotime("next sunday",$start_week);

            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);

            #echo $start_week.' '.$end_week ;            
        }elseif($duration == "m"){
            $start_week = date('Y-m-01',strtotime('last month'));
            $end_week = date('Y-m-t',strtotime('last month'));
        }
        #$start_week = "2020-06-01";
        // echo $start_week.' '.$end_week ;
        // die;
        $outlets = $this->CronModel->getOutLetsAdmin();
        //echo "<pre>";
        if(empty($outlets))
            return true;
        foreach ($outlets['res_arr'] as $key => $ol) {

            $activity =$this->BusinessAdminModel->GetOutLetSMSActivity([$ol['business_outlet_id']]);

            $ac = [];
            if($activity['success']){
                $activity = $activity['res_arr'];                        
                foreach ($activity as $key => $a) {
                    $ac[] = $a['outlet_id']."_".$a['services_number'];
                }
            }

            if($duration == 'w' && !in_array($ol['business_outlet_id']."_4", $ac)){               
                continue;
            }

            if($duration == 'm' && !in_array($ol['business_outlet_id']."_6", $ac)){
                continue;
            }



            $where = array(
                    'business_admin_id'  => $ol['business_admin_id'],
                    'business_outlet_id' => $ol['business_outlet_id'],
                    'start_week'    => $start_week,
                    'end_week'    => $end_week
                );
            $total_due_amount= $this->CronModel->GetTotalDueAmount($where);
            #print_r($total_due_amount);die;
            $total_due_amount= $total_due_amount['res_arr'][0]['total_due_amount'];
            
            $pending_amount_received=$this->CronModel->GetPendingAmountReceived($where);
            #print_r($pending_amount_received);die;
            $pending_amount_received=$pending_amount_received['res_arr'][0]['pending_amount_received'];
            $msg = "Pending Amount Update,  ".$ol['business_outlet_name'].", ".$ol['business_outlet_location'].", Duration: $start_week- $end_week 
                    Generated:Rs.$total_due_amount
                    Received: Rs.$pending_amount_received
                    Due Amt till dt: ".abs($total_due_amount-$pending_amount_received);
                    //echo $msg;
            $this->sendMessage($ol['business_admin_mobile'],$msg);
            //die;
            // echo $msg."<br><br>";
            // die;
        }
    }
}

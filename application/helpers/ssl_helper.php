<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('force_ssl'))
{
    function force_ssl()
    {
        $CI =& get_instance();
        $CI->config->config['base_url'] =
                 str_replace('http://', 'https://',
                 $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] != 443)
        {
            redirect($CI->uri->uri_string());
        }
    }
}

function remove_ssl()
{
    $CI =& get_instance();
    $CI->config->config['base_url'] =
                  str_replace('https://', 'http://',
                  $CI->config->config['base_url']);
    if ($_SERVER['SERVER_PORT'] != 80)
    {
        redirect($CI->uri->uri_string());
    }
}

function shortUrl($url){

  $cutly = "https://cutt.ly/api/api.php?key=573f0fc3387bba7b17526dd095e3a3dbc6ccf&short=$url&name=";
  $json = file_get_contents($cutly);
  $json = json_decode($json,true);  
//  print_r($json);
  //die;
  $link = str_replace('https://', 'http://', $json['url']['shortLink']);
  return $link;
}


function sentOtp(){
  #print_r($this->session->all_userdata());die;
  $ci = &get_instance();
  $mobile = $ci->session->userdata['logged_in']['employee_mobile'];
  $rand = mt_rand(100000,999999);  
  $msg = "OPT ".$rand." send from Salon First System, please verify and don't share with anyone!";
  $msg = rawurlencode($msg);   //This for encode your message content                     
      
      // API 
  $api_key = "4XA1l9jcXkChf9TLKcI9bw";
      $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api_key.'&senderid=SLNFST&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.$msg.'&route=1';
                    error_log("msgurl ============ ".$url);
          
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch,CURLOPT_POSTFIELDS,"");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
      $data = curl_exec($ch);
      $resp = json_decode($data,true);
      if($resp['ErrorMessage'] == "Success"){       
        $ci->session->set_userdata('OTP', $rand);
      }
      return json_encode($data);     
}

function verifyOPT($otp){
  $ci = &get_instance();
  $res = array('status' => false,'message'=>' verification failed,please try again!');
  if($otp ==  $ci->session->userdata('OTP')){
    $res = array('status' => true,'message'=>'successfully verified');
    $ci->session->unset_userdata('OTP');
  }
  return json_encode($res);
}
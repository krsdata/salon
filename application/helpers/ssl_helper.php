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

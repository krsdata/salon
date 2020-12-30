<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('moneyFormat'))
{
    function moneyFormat($amount)
    {
		
		$amount = number_format($amount,2);
		return $amount;
    }
}

function getActualPrice($price){
	return str_replace( ',', '', $price);
}

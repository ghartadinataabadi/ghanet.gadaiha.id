<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('basic_auth_post'))
{
    function basic_auth_post($url,$username,$password,$params)
    {
        //Initialize Header
        $headers = array("Authorization: Bearer P4FoN20PeeJQb3zoySj92UBOV4-7uHtmdhstq5vO3e0");
        //$headers = array("Authorization: Bearer ".$token."");

        //Initialize cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);  
        curl_setopt($curl, CURLOPT_URL, $params?sprintf("%s?%s", $url, http_build_query($params)):$url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);       
        curl_close($curl);        
        return $response;  
    }   
}

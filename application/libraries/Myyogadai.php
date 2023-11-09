<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myyogadai {

    protected $CI;

    public $api_client_secret;

    public $api_client_id;

    public $url;

    public $token;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('yogadai');
        $this->api_client_id = $this->CI->config->item('api_client_id');
        $this->api_client_secret = $this->CI->config->item('api_client_secret');
        $this->url = $this->CI->config->item('url');
        $this->token = $this->login()->access_token;
            // Do something with $params
    }

    public function login()
    {
         //Initialize Header
        $urlLogin = $this->url.'/oauth/token';

        $ch = curl_init();

        $form =  "grant_type=client_credentials&scope=api";

        $form .= "&client_id=".$this->api_client_id;
        $form .= "&client_secret=".$this->api_client_secret;


        curl_setopt($ch, CURLOPT_URL,$urlLogin);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                   $form);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        
        
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $res = json_decode(curl_exec ($ch));
        
        curl_close ($ch);

        return $res;
        
    }

    public function transaction($date = '')
    {
           $url = $this->url.'/api/v1/publishers/transactions/today';

           $date = $date ?  $date : date('Y-m-d');

           $dataArray = array(
               'date'   => $date
           );

           $data = http_build_query($dataArray);
           $getUrl = $url."?".$data;

           $crl = curl_init();

           $headr = array();
           $headr[] = 'Content-type: application/json';
           $headr[] = 'Authorization: Bearer '.$this->token;
           curl_setopt($crl, CURLOPT_URL,$getUrl);
           curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
           curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
           $res = curl_exec($crl);
           curl_close($crl);  
           
           return json_decode($res);
    }
    public function transaction_detail($dateStart = '', $dateEnd = '', $unitname = '', $transactionStatus, $page = 1)
    {
        $url = $this->url.'/api/v1/publishers/detail_transactions';
        $dataArray = array(
        );
        if($dateStart){
            $dataArray['start_date'] = $dateStart;
        }
        if($page){
            $dataArray['page'] = $page;
        }
        if($dateEnd){
            $dataArray['end_date'] = $dateEnd;
        }
        if($transactionStatus){
            $dataArray['transaction_status'] = $transactionStatus;
        }
        if($unitname){
            $dataArray['unit_name'] = $unitname;
        }
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        curl_close($crl);  
        return json_decode($res);
    }

    public function areas()
    {
        $url = $this->url.'/api/v1/publishers/areas';
        $dataArray = array(

        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        curl_close($crl);  
        return json_decode($res);
    }

    public function branchies($areaId)
    {
        $url = $this->url.'/api/v1/publishers/branches';
        $dataArray = array(
            'area_id'   => $areaId
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        curl_close($crl);  
        return json_decode($res);
    }

    public function units($branch_id)
    {
        $url = $this->url.'/api/v1/publishers/units';
        $dataArray = array(
            'branch_id'   => $branch_id
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;
        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        curl_close($crl);  
        return json_decode($res);
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syariah {

    protected $CI;

    public $username;

    public $password;

    public $url;

    public $token;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('gcore');
        $this->username = $this->CI->config->item('username');
        $this->password = $this->CI->config->item('password');
        $this->auth_syariah = $this->CI->config->item('auth_syariah');
        $this->master_syariah = $this->CI->config->item('master_syariah');
        $this->transaction_syariah = $this->CI->config->item('transaction_syariah');
        $this->accounting_syariah = $this->CI->config->item('accounting_syariah');
        $this->token = $this->login()->access_token;
            // Do something with $params
    }

    public function login()
    {
         //Initialize Header
        $urlLogin = $this->auth_syariah.'/oauth/token';

        $ch = curl_init();

        $form =  "grant_type=password";

        $form .= "&username=".$this->username;
        $form .= "&password=".$this->password;

        curl_setopt($ch, CURLOPT_URL,$urlLogin);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        
        
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $res = json_decode(curl_exec ($ch));
        curl_close ($ch);

        return $res;
        
    }

    public function areas()
    {
        $url = $this->master_syariah.'/api/v1/areas/autocomplete';
        $dataArray = array(
            'limit' => '50'
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        // $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        // var_dump($res); exit;
        // $httpCode = curl_getinfo($crl, CURLINFO_HTTP_CODE);

        //     if ( $httpCode != 200 ){
        //         echo "Return code is {$httpCode} \n"
        //             .curl_error($crl);
        //     } else {
        //         echo "<pre>".htmlspecialchars($res)."</pre>";
        //     }
            
        curl_close($crl);  
        return json_decode($res);
    }

    public function getareas($area)
    {
        $url = $this->master_syariah.'/api/v1/areas/autocomplete';
        $dataArray = array(
            'limit' => '50',
            'area_id' => $area
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        // $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        // var_dump($res); exit;
        // $httpCode = curl_getinfo($crl, CURLINFO_HTTP_CODE);

        //     if ( $httpCode != 200 ){
        //         echo "Return code is {$httpCode} \n"
        //             .curl_error($crl);
        //     } else {
        //         echo "<pre>".htmlspecialchars($res)."</pre>";
        //     }
            
        curl_close($crl);  
        return json_decode($res);
    }

    public function branchies($areaId)
    {
        $url = $this->master_syariah.'/api/v1/branch_offices/autocomplete';
        $dataArray = array(
            'limit'   => '50',        
            'area_id'   => $areaId
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        // $headr[] = 'Authorization: Bearer '.$this->token;
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
        $url = $this->master_syariah.'/api/v1/unit_offices/autocomplete';
        $dataArray = array(
            'branch_office_id'   => $branch_id
        );
        $data = http_build_query($dataArray);
        $getUrl = $url."?".$data;
        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        // $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($crl, CURLOPT_URL,$getUrl);
        curl_setopt($crl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string 
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
        $res = curl_exec($crl);
        curl_close($crl);  
        return json_decode($res);
    }

    //region
    public function region($page)
    {   
        $url = $this->master_syariah.'/api/v1/regionals';
        $dataArray = array(
            'page' => $page
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

    //product_id_electronic
    public function product_electronic_id()
    {   
        $url = $this->master_syariah.'/api/v1/product'; 
        $dataArray = array(
            'search' => 'Rahn Elektronik'
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

    //insurance_item
    public function insurance_item()
    {   
        $product = $this->product_electronic_id();
        $product_id = $product->data[0]->id->{'$oid'};

        $url = $this->master_syariah."/api/v1/product/$product_id/insurance_item/"; 
        // $dataArray = array(
        //     'page' => $page
        // );
        // $data = http_build_query($dataArray);
        // $getUrl = $url."?".$data;
        $getUrl = $url;

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
        // var_dump($res);exit;
        return json_decode($res);
    }

    //merk
    public function merk_hps($insurance_item_id)
    {   
        $url = $this->transaction_syariah."/api/v1/merk_hps"; 
        $dataArray = array(
            'insurance_item_id' => $insurance_item_id
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
        // var_dump($res);exit;
        return json_decode($res);
    }

    //data HPS
    public function hps($region, $merk, $search, $insurance, $processor)
    {
        
           $url = $this->transaction_syariah.'/api/v1/electronic_hps';
            

           $region = $region ?  $region : '';
           $merk = $merk ?  $merk : '';
           $insurance = $insurance ?  $insurance : '';
           $processor = $processor ?  $processor : '';
           
           $dataArray = array(
            'q[region_id_eq]' => $region,
            'q[insurance_item_id_eq]' => $insurance,
            );

            if (!empty($merk) || $merk != null || $merk != 0 ) {
                $dataArray['q[merk_eq]'] = $merk;
            }
            if (!empty($processor)) {
                $dataArray['q[processor_cont]'] = $processor;
            }
            if (!empty($search)) {
                $dataArray['search'] = $search;
            }
            

            // var_dump($dataArray); exit;

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

    public function uploadHps($file, $region, $insurance_item_id)
    {
        
        $url = $this->transaction_syariah.'/api/v1/electronic_hps/import';

        $file_name = $file['file']['name'];
        $file_type = $file['file']['type'];
        $file_path = $file['file']['tmp_name']; // path ke file Excel yang akan diunggah
               
        $ch = curl_init();

        $form = array(
            'file' => new CURLFILE($file_path, $file_type, $file_name),
            'region_id'=> $region,
            'insurance_item_id' => $insurance_item_id
        );

        $headr = array();
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $res = curl_exec($ch);

        // menutup ch
        curl_close($ch);
        return $res;

    }

    //insert hps
    public function insertHps($form)
    {
        
        $url = $this->transaction_syariah.'/api/v1/electronic_hps';
               
        $ch = curl_init();

        $headr = array();
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
       
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form));
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $res = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;

    }

    public function template()
    {
            // URL endpoint untuk export excel
           $url = $this->transaction_syariah.'/api/v1/electronic_hps/template';

           
            // Nama file Excel setelah diunduh
            $filename = "template_hps.xlsx";

            // Membuat objek cURL
            $ch = curl_init();

            $headr = array();
           
            $headr[] = 'Authorization: Bearer '.$this->token;
            
            // Mengatur opsi cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
            // Eksekusi cURL untuk mengunduh file
            $file_data = curl_exec($ch);

            // Tutup koneksi cURL
            curl_close($ch);

            // Simpan file Excel yang telah diunduh ke server
            file_put_contents($filename, $file_data);

            // Output pesan berhasil
            echo "File Excel telah diunduh";

    }

    public function show($id)
    {
        $url = $this->transaction_syariah."/api/v1/electronic_hps/$id";
        // $dataArray = array(
        //     'id' => $id
        // );
        // $data = http_build_query($dataArray);
        $getUrl = $url;

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

    public function update($id, $form)
    {
        
        $url = $this->transaction_syariah."/api/v1/electronic_hps/$id";
               
        $ch = curl_init();

        $headr = array();
        $headr[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
       
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form));
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;

    }

    public function delete($id)
    {
        $url = $this->transaction_syariah."/api/v1/electronic_hps/$id";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);
    }

   
}
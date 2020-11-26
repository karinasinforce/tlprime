<?php

namespace App\Services;

ini_set('max_execution_time', 60);

class UtilService
{
    
    public function getAuth()
    {

        $param              = array( 
            'url'           => env("API_PATH")."/Account/Login", 
            'inputs'        => array('user' => 'victor', 'pass' => 'victor'),
        );    
        $param_url      = array_get($param, 'url', '');

        $param_query    = array_get($param, 'inputs', '');
        $param_query    = "{'user':'victor', 'pass':'victor'}";
        $param_query    = "user=victor&passvictor";
        
        $header         = array('Content-Type:application/x-www-form-urlencoded');
        
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $param_url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $param_query,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_USERAGENT => 'cURL Request from Inforce Website',
            )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);        
        
        return $resp;
        
    }
    
    public function getResource($param=array())
    {

        $query_string   = "";
        $param_url      = array_get($param, 'url', '');
        $param_query    = json_decode(array_get($param, 'inputs', ''));

        //concatena a query string para a consulta na API
        if(!empty($param_query)) {
            foreach ($param_query as $key => $value) {
                $query_string .= "&".$key."=".$value;
            }            
        }
        if(empty($param_url)) {
            return "os parametros estão incorretos";
        }
        $param_url .= $query_string;

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $param_url,
            CURLOPT_HTTPHEADER => ['Content-Type:application/json'],
            CURLOPT_USERAGENT => 'cURL Request from Inforce Website',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
            
            )
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);                    

        return $resp;

    }
    
    public function postResource($param=array())
    {

        $param_url      = array_get($param, 'url', '');
        $param_fields   = array_get($param, 'fields', array());

        if(empty($param_url) || empty($param_fields)) {
            return "os parametros estão incorretos";
        }        
        
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $param_url,
            CURLOPT_USERAGENT => 'cURL Request from Inforce Website',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $param_fields
            )
        );
            // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);        
        
        return $resp;
        
    }
    
    static public function slugfy($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    
}

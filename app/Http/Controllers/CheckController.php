<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Services\UtilService as UtilService;

class CheckController extends BaseController
{
    public function check() 
    {

        $result = []; 
        $result['API'] = $this->checkApi(); 
        $result['GAPO'] = $this->checkGAPO(); 
        $result['SERVER'] = $_SERVER;
        
        $result['API_PATH'] = env("API_PATH"); 
        $result['API_PATH'] = str_replace('/api', '/demo', $result['API_PATH']);

        $result['GAPO_PATH'] = env("APP_ADMIN"); 

        $result['LOCAL'] = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? true : false; 
        $result['HTTPS'] = isset($_SERVER['HTTPS']) ? true : false;
        $result['CLOUDFLARE'] = isset($_SERVER['HTTP_CF_VISITOR']) ? true : false; 
        $result['CLOUDFLARE_HTTPS_ENABLED'] = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? true : false; 

        $result['STATUS'] = $result['API'] && $result['GAPO'] ? 'Verificado' : 'Negado'; 
        
        return view('layouts.pages.check')->with($result);
    }

    protected function checkApi()
    {
        
        try {
            //faz uma chamada basica para API Site2 do cliente
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Lancamento/Tipos", 
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return             = json_decode($json);

            return $return ? true : false;
        
        } catch(Exception $e) {

            return false; 
        }
    }


    protected function checkGAPO()
    {
        
        try {
            $param              = array( 
                'url'           => env("APP_ADMIN"), 
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            
            return $json ? true : false;
        
        } catch(Exception $e) {

            return false; 
        }
    }
}

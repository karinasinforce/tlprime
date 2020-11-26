<?php

namespace App\Services;

use Illuminate\Routing\Controller as BaseController;

use App\Services\UtilService as UtilService;

class CorretorService extends BaseController{

    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function index($arrParam=array()){
        try{
            $return             = array();
                       
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }


    /**
     * {@deprecated}
     * @param array $arrParam
     * @return array
     */
    public function view($arrParam=array()){
        try{
            $return             = array();        
            $CorretorApelido    = array_get($arrParam, 'Apelido', '');

            if($CorretorApelido){                
                $param              = array( 
                    'url'           => env("API_PATH")."/".env("API_KEY")."/Corretor/".$CorretorApelido, 
                );    
                
                $Util               = new UtilService();
                $json               = $Util->getResource( $param );                        
                
                $return             = json_decode($json);
            }
            
            
            return $return;            
        } catch (Exception $ex) {

            return $return;

        }
    }

    
    public function listCorretor($arrParam=[])
    {
        try{
            $return             = array();        
            $perfilMembroId    = array_get($arrParam, 'perfilMembro', 1);

            if($perfilMembroId) {                
                
                $param  = array( 
                    'url' => env("API_PATH")."/".env("API_KEY")."/pessoa?perfilMembro={$perfilMembroId}"
                );    
                
                $Util               = new UtilService();
                $json               = $Util->getResource( $param );                        
                
                $return             = json_decode($json);
            }
            
            return $return;            
        } catch (Exception $ex) {
            return $return;
        }
    }

    public function viewCorretor($arrParam=array()){
        try{
            $return             = array();        
            $slug    = array_get($arrParam, 'slug', '');

            if($slug) {                
                
                $param  = array( 
                    'url' => env("API_PATH")."/".env("API_KEY")."/pessoa/".$slug, 
                );    
                
                $Util               = new UtilService();
                $json               = $Util->getResource( $param );                        
                
                $return             = json_decode($json);
            }
            
            return $return;            
        } catch (Exception $ex) {
            return $return;
        }
    }
}

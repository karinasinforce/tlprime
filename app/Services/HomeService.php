<?php

namespace App\Services;

use Illuminate\Routing\Controller as BaseController;

use App\Services\UtilService as UtilService;

class HomeService extends BaseController{

    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function listService($arrParam=array()){
        try{
            $return             = array();
                       
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }


    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function viewService($arrParam=array()){
        try{
            $return = array();
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }
    
    
    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function editService($arrParam=array()){
        try{
            $return = array();
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }


    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function deleteService($arrParam=array()){
        try{
            $return = array();
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }

    /*
     * Retorna o banner do topo com um ou mais itens
     */
    public function bannerService($arrParam=array()){
        try{
            $return             = array();        
            $bannerTag          = array_get($arrParam, 'banner-tag', 'banner-home-principal');

            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Banner/Itens/".$bannerTag, 
            );    
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return['content']  = json_decode($json);
            
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }

    /*
     * Retorna uma vitrine
     */
    public function imoveisVitrineService($arrParam=array()){
        try{
            $return             = array();            
            $param              = array( 
                'url'           => env("API_PATH")."/Vitrine/Imovel/".env("API_KEY")."/_", 
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return['content']  = json_decode($json);
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }

    
}

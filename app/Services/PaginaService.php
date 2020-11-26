<?php

namespace App\Services;

use Illuminate\Routing\Controller as BaseController;

use App\Services\UtilService as UtilService;

class PaginaService extends BaseController{

   
    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function listService($arrParam=array()){
        try{
            $return             = array();
//            $param              = array( 
//                'url'           => env("API_PATH")."/".env("API_KEY")."/Noticia", 
//            );    
//            
//            $Util               = new UtilService();
//            $json               = $Util->getResource( $param );                        
//            $return             = json_decode($json);
//                                    
//            //print_r("<pre>");print_r($return);die;

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
            $return             = array();            
            $PaginaUrl          = array_get($arrParam, 'PaginaUrl', '');
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Pagina/Detalhe/".$PaginaUrl, 
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return             = json_decode($json);
            
            //print_r("<pre>");print_r($return);die;
            
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


    
}

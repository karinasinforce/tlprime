<?php

namespace App\Services;

use Illuminate\Routing\Controller as BaseController;

use App\Services\UtilService as UtilService;

class LancamentoService extends BaseController{

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
            $return             = array();            
            $ImovelID           = array_get($arrParam, 'ImovelID', '');
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Empreendimento/LancamentoDetalhe/".$ImovelID. "?includes=opcionistas", 
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

    /*
     * Retorna o banner do topo com um ou mais itens
     */
    public function bannerService($arrParam=array()){
        try{
            $return             = array();            
            $param              = array( 
                'url'           => env("API_PATH")."/Banners/Itens/".env("API_KEY")."/1", 
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
    public function VitrineService($arrParam=array()){
        try{
            $return             = array();

            $vitrineNome        = array_get($arrParam, 'vitrine-nome', 'vitrine-lancamento-destaque');
            $minimo             = array_get($arrParam, 'minimo', 8);
            
            if($vitrineNome){
                $param              = array( 
                    'url'           => env("API_PATH")."/".env("API_KEY")."/Vitrine/Lancamento/".$vitrineNome."?minimo=".$minimo,
                );

                $Util               = new UtilService();
                $json               = $Util->getResource( $param );                        
                $return['content']  = json_decode($json);
            }
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }
    }

    
}

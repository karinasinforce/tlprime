<?php

namespace App\Services;

use Illuminate\Routing\Controller as BaseController;

use App\Services\UtilService as UtilService;

class BlogService extends BaseController{

    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function listService($arrParam=array()){
        try{
            $return             = array();      
            $sufix = "";
            $defaultPagesize = 4;
            
            // caso passe o pagesize
            if( isset($arrParam['pagesize']) ) {
                $sufix .= "pagesize=" . $arrParam['pagesize'];
            } else {
                $sufix .= "pagesize=" . $defaultPagesize;
            }

            // caso passe a categoria, busca pela categoria
            if( isset($arrParam['categoria']) ) {
                $sufix .= "&categoriaId=" . $arrParam['categoria'];
            }
            
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Noticia?" . $sufix, 
            );   
            
            // \print_r($param['url']);
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );  
            
            $return             = json_decode($json);

            foreach($return as $key => $item) {
                $item->slug = UtilService::slugfy($item->Titulo);
            }
                                    
            return $return;

            
        } catch (Exception $ex) {

            return $return;

        }
    }

    public function listCategoriaService(){
        try{
            $return             = array();            
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/noticia/categorias", 
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return             = json_decode($json);
                                    
            //print_r("<pre>");print_r($return);die;
            foreach($return as $key => $item) {
                $item->slug = UtilService::slugfy($item->Nome);
            }
            return $return;

            
        } catch (Exception $ex) {

            return $return;

        }
    }

    public function getDestaks($qtd=6, $catId=false) {

        if ($catId) {
            $destak = $this->listService( [
                'categoria' => $catId
            ]);
        } else {
            $destak = $this->listService(["pagesize"=>100]);
        }

        $destak = array_filter($destak, function($i){
            return $i->FlagDestaque == '-1';
        });

        $return = array_slice($destak, 0, $qtd);
        
        if( $qtd == 1 ) {
            return current($return);
        } else {
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
            $NoticiaID          = array_get($arrParam, 'NoticiaID', '');
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Noticia/Detalhe/".$NoticiaID, 
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

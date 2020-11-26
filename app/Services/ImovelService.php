<?php

namespace App\Services;

use Illuminate\Routing\Controller as BaseController;
use App\Services\UtilService as UtilService;
use Exception;

class ImovelService extends BaseController{

    /**
     * 
     * @param array $arrParam
     * @return array
     */
    public function listService($arrParam=array()){
        try{            
            $url_api        = env("API_PATH")."/".env("API_KEY")."/Anuncio/";
            $url_apikey     = env("API_KEY");
            $url            = $url_api;
            $arr_inputs     = json_decode(array_get($arrParam, 'busca', ""));
            
            $finalidade     = array_get($arrParam, 'Finalidade', "");
            $tipo           = array_get($arrParam, 'Tipos', "");
            $uf             = array_get($arrParam, 'UF', "");
            
            $tipo           = ($tipo=='imoveis') ? '' : $tipo;
            
            $query_string   = "?Finalidade=$finalidade&Tipos=$tipo";
                       
            //concatena a query string para a consulta na API
            if(empty($arr_inputs)){
                $arr_inputs = array();
            }
            foreach ($arr_inputs as $key => $value) {
                if(($key=="Finalidade") || ($key=="Tipos")){
                    // do nothing
                }else{
                    $query_string .= ($query_string=="?") ? $key."=".$value : "&".$key."=".$value;                    
                }
            }
            if(empty($arr_inputs)){ $query_string = ""; }
                
            $url    .= $query_string;
            $uf     = $this->returnUF($uf);    
            
            $return             = array(
                'url_api'       => $url_api,
                'url_apikey'    => $url_apikey,
                'query_string'  => $query_string,
                'json'          => $arrParam,
                'inputs'        => $arr_inputs,
                'url_local'     => array(
                    'Finalidade'    => $finalidade,
                    'Tipos'         => $tipo,
                    'UF'            => $uf,
                ),
            );           
            
            return $return;
            
        } catch (Exception $ex) {

            return $ex->getMessage();

        }
    }
    
    
    public function selectTipos($arrParam=array()){
        try{
            //$request            = \Request::instance();
            $return             = array();            
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Imovel/Tipos/", 
            );    
            
            $Util               = new UtilService();            
            $json               = $Util->getResource( $param );                        
            $return             = json_decode($json);
            
            if(count($return) < 1){
                $return = array(
                    array('Tipo' => "Casa"),
                    array('Tipo' => "Apartamento"),
                    array('Tipo' => "Terreno"),
                );                
            }

            $arr = []; 
            foreach ($return as $key => $item) {
                $arr[$item->Natureza][] = $item ; 
            }
            
            $return = $arr;

            // dd($return);
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }        
        
    }

    /**
     * 
     * @param array $arrParam
     * @return array
     * http://gapoapi.inforcecode.com.br/api/victor/Imovel/3137
     */
    public function viewService($arrParam=array()){
        try{
            $return             = array();            
            $ImovelID           = array_get($arrParam, 'ImovelID', '');
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Imovel/Detalhe/".$ImovelID . "?includes=opcionistas", 
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return             = json_decode($json);

            if ($return->Video)
                $return->Video = $this->handleVideo($return->Video); 
            
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }        
        
    }


    /**
     * Trata 3 possibilidades de links de vídeo
     * 
     * YOUTUBE:
     * http://youtu.be/{codigo}
     * https://www.youtube.com/watch?v={codigo}
     * 
     * VIMEO:
     * https://vimeo.com/{codigo}
     * 
     */
    public function handleVideo($videoUrl)
    {
        switch ($videoUrl) {
            case strpos($videoUrl, 'https://www.youtube.com/watch?v='):
                return str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $videoUrl); 
                break;
            case strpos($videoUrl, 'http://youtu.be/'):
                return str_replace('http://youtu.be/', 'https://www.youtube.com/embed/', $videoUrl); 
                break;
            case strpos($videoUrl, 'https://vimeo.com/'):
                return str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $videoUrl); 
                break;
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

            $vitrineNome        = array_get($arrParam, 'vitrine-nome', 'vitrine-imovel-destaque');
            $minimo             = array_get($arrParam, 'minimo', 8);
            $maximo             = array_get($arrParam, 'maximo', 8);
            if(session('agent')->isMobile()){
                $maximo         = 8;
            }
            
            if($vitrineNome){
                $param              = array( 
                    'url'           => env("API_PATH")."/".env("API_KEY")."/Vitrine/Imovel/".$vitrineNome."?minimo=".$minimo."&maximo=".$maximo,
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
    
    /**
     * @deprecated
     */
    public function similaresService($arrParam=array()){
        try{
            $return             = array();

            $vitrineNome        = array_get($arrParam, 'vitrine-nome', 'imoveis-destaque');
            if($vitrineNome){
                $param              = array( 
                    'url'           => env("API_PATH")."/".env("API_KEY")."/Vitrine/Imoveis/".$vitrineNome,
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

    public function imoveisRelacionados($imovelId, $quantidadeImoveis=4)
    {
        try{
            $return             = array();
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/Imovel/{$imovelId}/relacionados?quantidadeImoveis={$quantidadeImoveis}",
            );    
            
            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return['content']  = json_decode($json);                
            
            return $return;
            
        } catch (Exception $ex) {
            return $return;
        }            
    }


    public function getCloudTags($arrParam=[])
    {
        try{
            $return             = array();

            $limit        = array_get($arrParam, 'limit', 10);
            
            $param              = array( 
                'url'           => env("API_PATH")."/".env("API_KEY")."/imovel/cloudtags?limit={$limit}",
            );    

            $Util               = new UtilService();
            $json               = $Util->getResource( $param );                        
            $return['content']  = json_decode($json);                
        
            return $return;
            
        } catch (Exception $ex) {

            return $return;

        }                
    }
    
    public function returnUF($uf){
        
        switch ($uf){
            case "maranhao":
                $uf = "MA";
                break;
            case "ceara":
                $uf = "CE";
                break;
            case "piaui":
                $uf = "PI";
                break;
            case "para":
                $uf = "PA";
                break;
        }

        return $uf;
    }
    
}

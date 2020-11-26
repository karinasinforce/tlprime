<?php

namespace App\Services;

ini_set('max_execution_time', 60);

use App\Services\UtilService;

class ParamService
{
    
    public $siteconfig      = 1;        //siteconfig desabilitado por enquanto
    public $manutencao      = 0;
    public $premium         = 0;
    public $userfavoritos   = 1;
    public $sitecorretor    = 1;
    public $sitefilial      = 0;
    
    
    function __construct()
    {
        
        $API            = env("API_PATH")."/".env("API_KEY")."/Parametro/All";        

        $UtilService    = new UtilService();
        $Param          = $UtilService->getResource(['url' => $API]);
        $Param          = ($Param) ? json_decode($Param) : array();
        
        foreach($Param as $key => $value){
            $this->$key = $value;
        }

    }

    /**
     *  Parametros do site definidos como ativos(1) ou inativos(0)
     *  Incluem, mas nao se limitam, a:<br><br>
     * <pre>
     * <b>Configs</b>
     *      Modo SiteConfig :: $Parametros->siteconfig
     *      Modo Manutencao :: $Parametros->manutencao
     *      Premium/Express :: $Parametros->premium
     * 
     * <b>Modulos</b>
     *      Favoritos       :: $Parametros->userfavoritos
     *      Corretor        :: $Parametros->sitecorretor
     *      Filial          :: $Parametros->sitefilial
     * </pre>
     * 
     * @param  type $Param
     * @return type
     */
    function procParametros($Param=null)
    {
        
        if(empty($Param)) {  

            $Param          = new self();
            
            // verifica se o parametro 'telefones' esta vazio
            if(empty($Param->telefones)) {
            }
            // se nao estiver vazio, verifica se o parametro 'telefones' eh um array (espera-se que no seja um JSON para tratar a informacao)
            elseif(!is_array($Param->telefones)) {
                $Param->telefones = json_decode($Param->telefones);
                // termina o processamento dos telefones
                foreach ($Param->telefones as $key => $telefone){
                    $key_assoc = key($telefone);
                    $Param->telefones[$key_assoc] = $telefone->$key_assoc;
                    unset($Param->telefones[$key]);
                }
            }

            // if (!empty($Param->nuvem_de_tags)) {
            //     $Param->nuvem_de_tags = json_decode($Param->nuvem_de_tags);
            //     // termina o processamento dos telefones
            //     foreach ($Param->nuvem_de_tags as $key => $tag){
            //         $key_assoc = key($tag);
            //         $Param->nuvem_de_tags[$key_assoc] = $tag->$key_assoc;
            //         unset($Param->nuvem_de_tags[$key]);
            //     }
            // }
            
            // verifica se o site eh EXPRESS ou PREMIUM e em caso de vazio o padrao eh EXPRESS
            if(empty($Param->classificacao)) {
                $Param->classificacao = 'EXPRESS';
            }
            
            // Se LOCAL, desabilita manutencao
            //if(env('APP_ENV')=='LOCAL'){
            //    $Param->manutencao = 0;
            //}
            
            
        }
        
        return $Param;
    }    
    
}

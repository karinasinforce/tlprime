<?php

namespace App\Http\Middleware;

use Closure;
//use Cookie;
use Jenssegers\Agent\Agent;
//use Illuminate\Support\Facades\Auth;

use App\Services\GuestService as Guest;
//use App\Services\UtilService;
use App\Services\ParamService;

use App\Http\Controllers\CorretorController as Corretor;

class DetectEnvironment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){        
        
    // MOBILE
        // instancia o modulo de detectar dispositivo e armazena numa session 
        $agent  = new Agent();                 
        $request->session()->put('agent', $agent);
        
        
            
    // TRACKING USUARIO
            $Guest          = new Guest();
            $GuestHash      = $Guest->setHash();

            // verifica se o usuario ja foi trackeado        
            if(empty($_COOKIE['guest'])){
                $Guest->token   = $GuestHash;
                // dd($Guest);
            }else{
                $Guest          = json_decode($_COOKIE['guest']);

                // Correção do ticket #6622
                if (!isset($Guest->token)) {
                    setcookie("guest", ""); 
                } else {

                    if(empty($Guest->token)){
                        $Guest->token   = $GuestHash;
                    } 
                }

                //unset($Guest); //unset($Guest->values);
            }  
            
            // seta a session com o token do usuario atual
            $request->session()->put('guest', $Guest);

        
            
    // TRACKING BUSCA
        // verifica se alguma busca foi feita
        if(empty($_COOKIE['search'])){}else{            
            $request->session()->put('search', json_decode($_COOKIE['search']));
        }
        
        
        
    // SITE PARAMETROS
        // Consulta a API em busca dos parametros setados pelo GAPO
        $ParamService               = new ParamService();
        $Parametros                 = $ParamService->procParametros();        

        // Seta os parametros em uma session
        $request->session()->put('parametros', $Parametros);        

        // Verifica se está em modo MANUT
        if(!empty($Parametros->manutencao) || env('APP_MANUT')=='TRUE'){
            return redirect('/manutencao');
        }        
        
        // se o site ainda nao tem os parametros configurados, abre a tela de config
        if(empty($Parametros->siteconfig)){
            return redirect('/configuracoes');
        }        
            
            
    // PARAMETROS PARA SITE PREMIUM
        // no momento busca do arquivo ENV
        if(env('APP_LEVEL')=='PREMIUM'){

            // CORRETOR
                // verifica se algum parametro de corretor foi requisitado
                $corretor = $request->input('corretor');            
                
                if($corretor){
                    $Corretor = new Corretor();
                    $Corretor->view($corretor);
                }            
                
        }else{
            
            // CORRETOR
                //apaga o corretor da session
                $request = \Request::instance();
                $request->session()->forget('corretor');
                
        }

        return $next($request);
    }


}

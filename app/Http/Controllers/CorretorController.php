<?php
/*
 * Controller do Site do Corretor 
 * 
 * O site do corretor e um sistema que altera levemente o site, colocando dados do corretor no lugar dos dados da empresa
 * 
 * Usa Input na Busca Avancada, para filtrar nas listagens e setar o session
 * Altera o Controller Imovel, verificando nas listagens se existe um input do corretor preenchido (NomeCorretor)
 * 
 * Se for passado uma querystring "&corretor=<nome>" ou uma rota "/corretor/<nome>", ele carrega o site do corretor tambem
 * 
 *  */


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Services\CorretorService;

class CorretorController extends BaseController{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    /**
     * Metodo que carrega o corretor no site
     * @param type $nome
     */
    public function view($apelido, $ouroirect=true){
        
        if(empty($apelido)){
            return NULL;
        }
        
        $arrParam = array(
            'Apelido' => $apelido,
        );

        //busca o corretor via MOCK
        $Corretor           = json_decode('{"ID": 171, "Nome":"Pedro Alvarez Cabral Lima Itália", "Apelido":"Pedro Itália", "Email":"inforce@inforce.com.br", "TelResidencial":"(21)3333-3333", "TelCelular":"(21)99999-9999", "TelComerciaj":"(21)99999-9998"}, "CpfCnpj":"", "Creci":"" ');
        
        //busca o corretor via API
        $CorretorService    = new CorretorService();
        $Corretor           = $CorretorService->view($arrParam);
        
        //print_r("<pre>"); print_r($Corretor); die;

        if(empty($Corretor)){
            return NULL;
        }

        //verifica o avatar e seta um avatar padrao
        $Corretor->Avatar = array_get($Corretor, "Avatar", asset('/img/placeholders/default_avatar.png'));

        //outros parametros
        $Corretor->Posicao = array_get($Corretor, "Posicao", "Topo");
               
        //registra o corretor na session
        $request = \Request::instance();
        $request->session()->put('corretor', $Corretor);
                
        if($ouroirect){            
            return redirect("/");
        }                    
    }
    
    
    /**
     * Metodo que remove o corretor do site
     */
    public function reset(){
        
        //apaga o corretor da session
        $request = \Request::instance();
        $request->session()->forget('corretor');
        
        return redirect("/");
    }
    
    

    public function index()
    {
        // do some requests
        $return             = array( 'data' => array(
            'APP_DEBUG'     => env('APP_DEBUG'),
            'IMG_PATH'      => env('IMG_PATH'),
            'isMobile'      => session('agent')->isMobile(),
            'Guest'         => json_encode(session('guest'))
        )); 

        $CorretorService = new CorretorService();
        $corretoresList = $CorretorService->listCorretor(['perfilMembro' => 1]); 

        $return['content']  = $corretoresList;

        // send view some data
        return view('layouts.corretores.lista')->with($return);       
    }


    public function viewCorretor($slug)
    {
        // do some requests
        $return             = array( 'data' => array(
            'APP_DEBUG'     => env('APP_DEBUG'),
            'IMG_PATH'      => env('IMG_PATH'),
            'isMobile'      => session('agent')->isMobile(),
            'Guest'         => json_encode(session('guest'))
        )); 
       
        $CorretorService = new CorretorService();
        $corretor = $CorretorService->viewCorretor(['slug' => $slug]); 

        $imoveis = $corretor->Imoveis; 

        $group = []; 
        $group['venda'] = []; 
        $group['locacao'] = []; 
        $group['total'] = count($corretor->Imoveis); 

        foreach ($imoveis as $imovel) {
            
            if ($imovel->ValorLocacao) {
                $group['locacao'][] = $imovel;
            }

            if ($imovel->ValorVenda) {
                $group['venda'][] = $imovel;
            }
        }

        $group['totalVenda'] = count($group['venda']); 
        $group['totalLocacao'] = count($group['locacao']); 

        $corretor->Imoveis = $group; 
        $return['content']  = $corretor;
        
        // send view some data
        return view('layouts.corretores.interna')->with($return);       
    }
}

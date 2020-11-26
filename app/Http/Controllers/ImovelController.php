<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;

use App\Services\ImovelService;
use App\Services\LancamentoService;
use \App\Services\HomeService as HomeService;

use \App\Services\UtilService as UtilService;

class ImovelController extends BaseController{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    public function index($tipo="imoveis", $uf=""){
        
        // do some requests
        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        
        $arrParam['busca']          = $request->input('busca');        

        $localizacao = "";
        if (isset($arrParam['busca']) && !empty($arrParam['busca'])) {

            $buscaJson = json_decode($arrParam['busca'], true); 
            
            if (isset($buscaJson['AutoSuggest']) && is_array($buscaJson['AutoSuggest'])) {
                $buscaJson['AutoSuggest'] = json_encode($buscaJson['AutoSuggest']); 
            }

            if (isset($buscaJson['AutoSuggest'])) {
                $autosuggest = current(json_decode($buscaJson['AutoSuggest']));
                
                if ($autosuggest && isset($autosuggest->Nome))
                    $localizacao = $autosuggest->Nome; 
            }

            $arrParam['busca'] = json_encode($buscaJson); 
        }


        // se o site for PREMIUM
        if(env('APP_LEVEL')=='PREMIUM'){            
            // verifica se existe um corretor associado        
            $NomeCorretor               = $request->input("NomeCorretor");
            //
            if(empty($NomeCorretor) ){
                if(!empty($arrParam['busca'])){
                    $objBusca = json_decode($arrParam['busca']);
                    if(!empty($objBusca->NomeCorretor)){
                        $NomeCorretor   = $objBusca->NomeCorretor;
                    }
                }                
            }
            
            $Corretor                   = new \App\Http\Controllers\CorretorController();
            $Corretor->view($NomeCorretor, false);
        }

        // build return param
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> $this->getBreadcrumb(),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));        
        
        // trata finalidade
        $finalidade                 = Route::currentRouteName();
        $arrParam['Finalidade']     = empty($finalidade) ? "venda" : str_replace("/", "", $finalidade);
        
        $arrParam['Tipos']          = $tipo;
        
        empty($uf)                  ? NULL : $arrParam['UF'] = $uf;
        
        $ImovelService              = new ImovelService();
        $return['info_api']          = $ImovelService->listService($arrParam);        
        $return['selectTipos']      = $ImovelService->selectTipos($arrParam);

        $return['localizacao'] = $localizacao;

        // send view some data
        return view('layouts.prontos.base')->with($return);
        
    }
    
    /**
     * Meotod especifico para listar imoveis por UF 
     *
     * @param type $uf
     * @return type
     */
    public function indexUF($uf=""){
        
        // do some requests
        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        
        // se o site for PREMIUM
        if(env('APP_LEVEL')=='PREMIUM'){            
            // verifica se existe um corretor associado        
            $NomeCorretor               = $request->input("NomeCorretor");
            //
            if(empty($NomeCorretor) ){
                if(!empty($arrParam['busca'])){
                    $objBusca = json_decode($arrParam['busca']);
                    if(!empty($objBusca->NomeCorretor)){
                        $NomeCorretor   = $objBusca->NomeCorretor;
                    }
                }                
            }
            
            $Corretor                   = new \App\Http\Controllers\CorretorController();
            $Corretor->view($NomeCorretor, false);
        }      

        // build return param
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> $this->getBreadcrumb(),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));        
        
        // trata finalidade
        $arrParam['Finalidade']     = "venda";        
        $arrParam['Tipos']          = "";
        $arrParam['UF']             = $uf;
        
        if(empty($uf)){
            return redirect('venda/');
        }
        
        $arrParam['busca']          = $request->input('busca');
        
        $ImovelService              = new ImovelService();
        $return['content']          = $ImovelService->listService($arrParam);        
        $return['selectTipos']      = $ImovelService->selectTipos($arrParam);
        
        //print_r("<pre>"); print_r($return); die;
        
        // send view some data
        return view('layouts.prontos.base')->with($return);
        
    }
    
    
    public function viewImovel($tag=null,$tagid=null){
                
        $tagid                      = explode("-",$tagid);
        $id                         = array_get($tagid,"1",0);

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> $this->getBreadcrumbCustom('Imóveis'),
            'search'    => session('search'),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));        

        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['ImovelID']       = $id;
        $arrParam['Finalidade']     = "venda";
                       
        $ImovelService              = new ImovelService();
        $Imovel                     = $ImovelService->viewService($arrParam);
        
        $return['content']          = $Imovel;
        $return['relacionados']     = $ImovelService->imoveisRelacionados($Imovel->ID);
        
        $return['selectTipos']      = $ImovelService->selectTipos($arrParam);

        $return['info_api']          = $ImovelService->listService($arrParam);   
        
        
        // send view some data
        return view('layouts.prontos.base')->with($return);
        
    }
    
    
    public function viewLancamento($tag=null,$tagid=null){
        
        //print_r("<pre>"); print_r($id); die;
        $tagid                      = explode("-",$tagid);
        $id                         = array_get($tagid,"1",0);

        
        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb' => $this->getBreadcrumbCustom('Lançamentos'),
            'isMobile'  => session('agent')->isMobile(),
            'isTablet'  => session('agent')->isTablet(),            
            'Guest'     => json_encode(session('guest')),
            
        ));        

        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['ImovelID']       = $id;
        $arrParam['Finalidade']     = "venda";
                       
        $LancamentoService          = new LancamentoService();
        
        $content = $LancamentoService->viewService($arrParam);

        $content->statusClass = UtilService::slugfy($content->StatusNome);
        $return['content'] = $content;
   
        // send view some data
        return view('layouts.lancamentos.base')->with($return);

        
    }

    public function viewLand($tag=null,$tagid=null){
        
        $tagid                      = explode("-",$tagid);
        $id                         = array_get($tagid,"1",0);

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'APP_ADMIN'  => env('APP_ADMIN'),
            'breadcrumb' => $this->getBreadcrumbCustom('Lançamentos'),
            'isMobile'  => session('agent')->isMobile(),
            'isTablet'  => session('agent')->isTablet(),            
            'Guest'     => json_encode(session('guest')),
        ));

        
        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['ImovelID']       = $id;
        $arrParam['Finalidade']     = "venda";
                       
        $LancamentoService          = new LancamentoService();
        $return['content']          = $LancamentoService->viewService($arrParam);
        
        //print_r("<pre>"); print_r($arrParam); die;
        //print_r("<pre>"); print_r($return['content']); die;
        
        // send view some data
        return view('layouts.land-nova.base')->with($return);
    }


    public function anuncioVenda($tag=null,$tagid=null){

        //print_r("<pre>"); print_r($id); die;

        $tagid                      = explode("-",$tagid);
        $id                         = array_get($tagid,"1",0);

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> $this->getBreadcrumb($id),
            'search'    => session('search'),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),

        ));

        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['ImovelID']       = $id;
        $arrParam['Finalidade']     = "venda";

        $ImovelService              = new ImovelService();

        $ImoveisVitrine             = $ImovelService->VitrineService(array('vitrine-nome' => 'vitrine-imovel-interna-venda'));

        $return['content']   = $ImoveisVitrine['content'];

        $HomeService        = new HomeService();
        $return['banner']   = $HomeService->bannerService(array('banner-tag' => 'banner-interna-prontos'));

        //$return['ImoveisSimilares'] = $ImovelService->similaresService( array() );

        //$return['selectTipos']      = $ImovelService->selectTipos($arrParam);

        //dd($return);

        // send view some data
        return view('layouts.prontos.vitrine-internas-venda')->with($return);

    }


    public function anuncioLocacao($tag=null,$tagid=null){

        $tagid                      = explode("-",$tagid);
        $id                         = array_get($tagid,"1",0);

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> $this->getBreadcrumb($id),
            'search'    => session('search'),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),

        ));

        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['ImovelID']       = $id;
        $arrParam['Finalidade']     = "locacao";

        $ImovelService              = new ImovelService();

        $ImoveisVitrine             = $ImovelService->VitrineService(array('vitrine-nome' => 'vitrine-imovel-interna-locacao'));

        $return['content']  = $ImoveisVitrine['content'];

        $HomeService        = new HomeService();
        $return['banner']   = $HomeService->bannerService(array('banner-tag' => 'banner-interna-locacao'));
           

        //$return['ImoveisSimilares'] = $ImovelService->similaresService( array() );

        //$return['selectTipos']      = $ImovelService->selectTipos($arrParam);

       // dd($return);

        // send view some data
        return view('layouts.prontos.vitrine-internas-locacao')->with($return);

    }

    public function anuncioLancamento($tag=null,$tagid=null){

        $tagid                      = explode("-",$tagid);
        $id                         = array_get($tagid,"1",0);

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> $this->getBreadcrumb($id),
            'search'    => session('search'),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),

        ));

        $request                    = \Request::instance();
        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['ImovelID']       = $id;
        $arrParam['Finalidade']     = "lancamentos";

        $ImovelService              = new LancamentoService();

        $ImoveisVitrine             = $ImovelService->VitrineService(array('vitrine-nome' => 'vitrine-imovel-interna-lancamento'));

        $return['content']   = $ImoveisVitrine['content'];

        $HomeService        = new HomeService();
        $return['banner']   = $HomeService->bannerService(array('banner-tag' => 'banner-interna-lancamento'));

        //$return['ImoveisSimilares'] = $ImovelService->similaresService( array() );

        //$return['selectTipos']      = $ImovelService->selectTipos($arrParam);

        // dd($return);

        // send view some data
        return view('layouts.prontos.vitrine-internas-lancamento')->with($return);

    }
    
    /**
     * Metodo de validacao do controller
     * 
     * @param type $request
     */
    public function validate($request){

        $rules = array(
            'name'          => 'required|min:3',
            'email'         => 'required|email',
            //'subject'       => 'required|min:3',
            //'obs'           => 'required',
            //'from_address'  => 'required|email',
            //'from_name'     => 'required',
            //'cc'            => 'required',
            //'bcc'           => 'required',
            //'cnpj'          => 'required|unique:users|regex:/^[0-9]{2}.[0-9]{3}+.[0-9]{3}+\/[0-9]{4}\-[0-9]{2}+$/',
        );

        $messages = array(
            'required'              => 'O campo :attribute é obrigatório.',
            'min'                   => 'O campo :attribute precisa ao menos de :min caracteres.',
            'from_address.email'    => 'O campo :attribute é inválido.',
        );
        
        return Validator::make($request->all(), $rules, $messages);
        
    }    
    
    
    public function getBreadcrumbCustom($finalidade=null){
        $breadcrumb = '';

        if ($finalidade and $finalidade == 'Lançamentos') {   
            $breadcrumb = '<li><a href="/lancamentos">'. $finalidade. '</a></li>';
        }
        
        if ($finalidade and $finalidade == 'Imóveis') {   
            $breadcrumb = '<li><a href="/imoveis">'. $finalidade. '</a></li>';
        }

        return $breadcrumb;
    }

    public function getBreadcrumb($id=null){
        
        $breadcrumb = '<li><a href="/imoveis">Imóveis</a></li>';
        
        if($id){
            $breadcrumb .='<li>ID-'.$id.'</li>';                    
        }

        // if ($nome) {
        //     # code...
        // }
        
        return $breadcrumb;
        
    }

    
    
}

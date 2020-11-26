<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Services\PaginaService;
use App\Services\ImovelService;
use Illuminate\Support\Facades\Validator;

class PageController extends BaseController{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    public function index(){
        return redirect('home');
    }
    
    
    public function viewPage($PaginaUrl){

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));
        
                        
        //busca a lista de blogs / noticias        
        $PaginaService          = new PaginaService();
        $return['content']      = $PaginaService->viewService(array('PaginaUrl' => $PaginaUrl));
        
        // send view some data
        return view('layouts.pages.custom')->with($return);

    }
    
    public function associado(){

        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> '<li>Seja nosso Associado</li>',
            'Titulo'    => 'Seja nosso Associado',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));  
        
       
        // dd($return);
        
        // send view some data
        return view('layouts.pages.seja-nosso-associado')->with($return);

    }

    /**
     * Retorna a pagina de manutencao
     * @return type
     */
    public function viewManut(){
        
        $Parametros = session('parametros');
        empty($Parametros->manutencao) ? $modoManutencao = 0 : $modoManutencao = $Parametros->manutencao;
        
        if($modoManutencao){
            // se o modo manutencao estiver habilitado
            $Parametros->manutencao = 0;
            session()->put('parametros', $Parametros);
            return view('errors.manutencao');
            
        }else{
            // caso contrario, retorna pra home
            return redirect('/');
        }
    }    
    
    

    /**
     * Retorna a pagina de manutencao
     * @return type
     */
    public function viewConfig(){
        
        $Parametros = session('parametros');          
        
        //print_r("<pre>");
        //print_r($Parametros);
        //print_r("</pre>");
        
        return view('errors.configuracoes')->with((array)$Parametros);
            
    }    

    /**
     * Posta as configurações no BD
     * @return type
     */
    public function postConfig(){
        try{
            print_r("<h1>post</h1>");

            $request    = \Request::instance();                
            $inputs     = $request->input();
            
            unset($inputs['_token']);

            print_r("<pre>");
            print_r($inputs);
            print_r(json_encode($inputs));
            print_r("<br><br>".ENV("APP_KEY"));
            die;
            
            return redirect('/');
            
        } catch (\Exception $ex) {
            
            echo $ex->getMessage();
        }
    }    
        
    
    public function viewFaleConosco(){
        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> '<li>Fale Conosco</li>',
            'Titulo'    => 'Fale Conosco',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));          
        
        //print_r("<pre>");
        //print_r($return['data']['Guest']);
        //print_r("</pre>");
        
        // send view some data
        return view('layouts.pages.fale-conosco')->with($return);        
    }

    public function viewTrabalheConosco(){        
        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> '<li>Trabalhe Conosco</li>',
            'Titulo'    => 'Trabalhe Conosco',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));          
        
        // send view some data
        return view('layouts.pages.trabalhe-conosco')->with($return);        
    }

    public function viewNewsletter(){
        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> '<li>Receba Novidades</li>',
            'Titulo'    => 'Receba Novidades',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));          
        
        // send view some data
        return view('layouts.pages.newsletter')->with($return);        
    }


   public function viewAnuncie(){
        // do some requests
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'breadcrumb'=> '<li>Anuncie seu Imóvel</li>',
            'Titulo'    => 'Anuncie seu Imóvel',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));          
        
        $ImovelService              = new ImovelService();
        $return['selectTipos']      = $ImovelService->selectTipos();        
        
        // send view some data
        return view('layouts.pages.anuncie-seu-imovel')->with($return);        
    }

    
    public function viewTimeout(){
        // send view some data
        return view('errors.timeout');
    }

    
  
    /**
     * Metodo que retorna a lista de favoritos do usuario
     * @param type $tag
     * @param type $tagid
     * @return type
     */
    public function viewFavoritos(){
        $Parametros = session('parametros');
        empty($Parametros->userfavoritos) ? $userfavoritos = 0 : $userfavoritos = $Parametros->userfavoritos;
                
        if($userfavoritos){
            
            // do some requests
            $Guest          = session('guest');
            
            $return         = array( 'data' => array(
                'APP_DEBUG' => env('APP_DEBUG'),
                'IMG_PATH'  => env('IMG_PATH'),
                'breadcrumb'=> '<li>Meus Favoritos</li>',
                'search'    => session('search'),
                'isMobile'  => session('agent')->isMobile(),
                'Guest'     => json_encode(session('guest')),
                'Favoritos' => $Guest->favoritos,

            ));              

            //print_r("<pre>");
            //print_r($return['data']['Favoritos']);
            //print_r("</pre>");

            // send view some data
            return view('layouts.pages.favoritos')->with($return);
            
        }else{
            // caso contrario, retorna pra home
            return redirect('/');
        }
        
        
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
    
}

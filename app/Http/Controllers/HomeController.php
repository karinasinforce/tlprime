<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

use \App\Services\HomeService as HomeService;
use \App\Services\ImovelService as ImovelService;
use \App\Services\LancamentoService as LancamentoService;
use \App\Services\BlogService as BlogService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;


class HomeController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    public function index($account=null)
    {
        // do some requests
        $request                    = Request::instance();
        
        $return         = array( 'data' => array(
            'APP_DEBUG' => env('APP_DEBUG'),
            'IMG_PATH'  => env('IMG_PATH'),
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            
        ));        
        $url_api        = env("API_PATH");
        $url_apikey     = env("API_KEY");
        
        // dd($url_api);

        $inputs                     = $request->input();
        $arrParam                   = $inputs;
        $arrParam['Finalidade']     = "venda";
        $arrParam['Tipos']          = "imoveis";
        $arrParam['Regioes']        = "";
        
        //busca os destaques e as vitrines
        //busca os destaques e as vitrines
        $HomeService                        = new HomeService();
        $bannerVitrine                      = $HomeService->bannerService(array('banner-tag' => 'banner-home-principal'));

       
        $LancamentoService                  = new LancamentoService();
        $LancamentoVitrine                  = $LancamentoService->VitrineService(array('vitrine-nome' => '4'));

        $ImovelService                      = new ImovelService();
        $ProntosVitrine                     = $ImovelService->VitrineService(array('vitrine-nome' => '1'));
        $LocacaoVitrine                     = $ImovelService->VitrineService(array('vitrine-nome' => '3'));

        $CloudTags                          = $ImovelService->getCloudTags(['limit' => 12]);


        $BlogService        = new BlogService();
        $posts = $BlogService->listService(["pagesize"=>3, "categoria" => 2]);
        // dd($posts);

        

        if ($posts) {

            $posts = array_filter(
                $posts, function ($item) {
                    return $item->FlagDestaque != 0; 
                }
            );
            
            $posts = array_slice($posts, 0, 3); 
        }
        // dd($posts);

        

        $return['bannerVitrine']            = $bannerVitrine;
        $return['vitrineLancamentos']       = $LancamentoVitrine;
        $return['vitrineProntos']           = $ProntosVitrine;
        $return['vitrineLocacao']           = $LocacaoVitrine;

        $return['cloudTags']                = $CloudTags;
        $return['selectTipos']              = $ImovelService->selectTipos($arrParam);
        $return['corretor']                 = $account;
        
        $return['posts']                         = $posts; 
    
        $return['content']                  = array(
            'url_api'                       => $url_api .'/'. $url_apikey . '/Anuncio',
            'url_apikey'                    => $url_apikey,
        ); 
       
        // send view some data
        // por padrao, o template busca primeiro na pasta resources/views/custom
        // se nao achar, procura em resources/views/core -> arquivos templates que NAO devem ser alterados
        return view('layouts.home.base')->with($return);        
    }


    /**
     * Metodo que gera o sitemap
     * As paginas que serao listadas no sitemap sao as que tem rota nomeada, no arquivo routes/web.php
     */
    public function sitemap($formato='xml')
    {

        $routeCollection = Route::getRoutes();

        // create new sitemap object
        $sitemap = App::make("sitemap");

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap', 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {

            // add item to the sitemap (url, date, priority, freq)
            foreach ($routeCollection as $value) {
                if($value->getName()) {
                    $sitemap->add(URL::to($value->getName()), date('Y-m-d H:i:s'), '0.8', 'weekly');
                }
            }
            //$sitemap->add(URL::to('/home'),     '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render($formato);
    }

    
    /**
     * Metodo de validacao do controller
     * 
     * @param type $request
     */
    public function validate($request)
    {

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

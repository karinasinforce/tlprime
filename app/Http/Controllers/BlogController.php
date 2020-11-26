<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use \App\Services\BlogService as BlogService;

use \App\Services\UtilService as UtilService;

class BlogController extends BaseController
{
    
    
    public function index()
    {
        
        // do some requests
        $return             = array( 'data' => array(
            'breadcrumb'    => '<li>Blog</li>',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            'IMG_PATH'  => env('IMG_PATH'),
            'API_PATH'  => env('API_PATH'),
            'API_KEY'  => env('API_KEY')
        ));        
        
        $BlogService        = new BlogService();

        $posts = $BlogService->listService(["pagesize"=>10, "categoria" => 2]);
        //$content = array_slice($posts, 0, 4);
        $content = $posts;
        
        //busca a lista de blogs / noticias        
        $return['content']  = $content;
     
        // dd();
        
        
        $return['sidebar']['destaks']  = $BlogService->getDestaks(6);
        
        $return['categorias']  = $BlogService->listCategoriaService();
        $return['json']     = json_encode($content);
        
        $return['destak'] = $BlogService->getDestaks(1);

        // send view some data
        return view('layouts.blog.base')->with($return);
        
    }


    public function categoria($slugCat)
    {
        
        // do some requests
        $return             = array( 'data' => array(
            'breadcrumb'    => '<li>Blog</li>',
            'isMobile'  => session('agent')->isMobile(),
            'Guest'     => json_encode(session('guest')),
            'IMG_PATH'  => env('IMG_PATH'),
            'API_PATH'  => env('API_PATH'),
            'API_KEY'  => env('API_KEY')
        ));
        
        //busca a lista de blogs / noticias        
        $BlogService        = new BlogService();
        $return['content']  = [];
        $return['categorias']  = $BlogService->listCategoriaService();
        $return['sidebar']['destaks']  = $BlogService->getDestaks(6);
        $return['slugCat'] = $slugCat;
        
        $util = new UtilService();
        foreach ($return['categorias'] as $key => $cat) {
            $cat->slug = $cat->UrlAmigavel; 
        }
        // filtra a categoria pelo slug pra pegar o id
        $idCatAtual = array_filter(
            $return['categorias'], function ($item) use ($slugCat) {
                return $item->slug == $slugCat;
            }
        );
    
        // se tiver realmente uma categoria nessa url, faz a busca
        if($idCatAtual) {
            $idCatAtual = current($idCatAtual);
            // busca o id pelo slug da categoria para buscar na api
            $return['content'] = $BlogService->listService(
                [
                'categoria' => $idCatAtual->Id
                 ]
            );
            $return['data']['categoria'] = $idCatAtual;
            $return['destak'] = $BlogService->getDestaks(1, $idCatAtual->Id);
        } 
       
        $return['json']     = json_encode($return['content']);
        
        // send view some data
        return view('layouts.blog.base-categorias')->with($return);
        
    }
        
    public function view($tag=null, $tagid=null)
    {
        // do some requests
        $return             = array( 'data' => array(
            'APP_DEBUG'     => env('APP_DEBUG'),
            'IMG_PATH'      => env('IMG_PATH'),
            'isMobile'      => session('agent')->isMobile(),
            'Guest'         => json_encode(session('guest'))
        )); 
        
        //busca a lista de blogs / noticias        
        $BlogService                    = new BlogService();
        $return['content']              = $BlogService->viewService(array('NoticiaID' => $tagid));
        $return['listContent']          = $BlogService->listService();
   
        $return['sidebar']['destaks']  = $BlogService->getDestaks(6);
        
        $blogTitulo                     = $return['content']->Titulo;
      
        // seta o breadcrumb
        $return['data']['breadcrumb']   = '<li><a href="/blog">Blog</a></li><li>'.$blogTitulo.'</li>';
        
        // send view some data
        return view('layouts.blog.base_view')->with($return);        
        
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

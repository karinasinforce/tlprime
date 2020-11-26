<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\Route;


//rota de exemplo
//Route::get('/', function () {
//    return view('welcome');
//});

/* ROTAS LIVRES */
    Route::get ('/manutencao',                                  'PageController@viewManut'          );
    Route::get ('/configuracoes',                               'PageController@viewConfig'         );
    Route::post('/configuracoes',                               'PageController@postConfig'         );

    Route::get ('/_app/check',                                  'CheckController@check'             );

/* ROTAS PUBLICAS */
Route::group(['middleware' => 'detect'], function () {
    
    //login
    //Route::auth();
    //Route::get('/logout',                           'Auth\LoginController@logout');
    //Route::get('/login',                            'Auth\LoginController@login');
    
    //GAPO
    Route::get('/admin',                                        function () {return redirect(ENV('APP_ADMIN'));});

    //home
    Route::get ('/',                                            'HomeController@index'              )->name('/');
    Route::get ('/home',                                        'HomeController@index'              )->name('/home');
    Route::get ('/sitemap.{formato?}',                          'HomeController@sitemap'            );

    // Internas vitrines de imoveis
    Route::get ('/anuncio/venda',                               'ImovelController@anuncioVenda'            )->name('/anuncio/venda');
    Route::get ('/anuncio/aluguel',                             'ImovelController@anuncioLocacao'          )->name('/anuncio/aluguel');
    Route::get ('/anuncio/lancamentos',                         'ImovelController@anuncioLancamento'       )->name('/anuncio/lancamentos');
    
    //imovel - venda
    Route::get ('/venda/{tipo?}',                               'ImovelController@index'            )->name('/venda');    
    //imovel - locacao
    Route::get ('/aluguel/{tipo?}',                             'ImovelController@index'            )->name('/aluguel');    
    //imovel - lista regiao
    Route::get ('/imoveis/{uf?}/',                              'ImovelController@indexUF'          )->name('/imoveis');
    //imovel - novo
    Route::get ('/lancamentos/{tipo?}',                         'ImovelController@index'            )->name('/lancamentos');


    //imovel - interna
    Route::get ('/imovel/{tag}/{id}',                           'ImovelController@viewImovel'       );
    
    //lancamentos/empreendimentos
    Route::get ('/lancamento/{tag}/{id}',                       'ImovelController@viewLancamento'   );
    
    //landing page de lançamentos 
    Route::get ('/land/{tag}/{id}',                             'ImovelController@viewLand'   );

    //blog
    Route::get ('/blog',                                        'BlogController@index'              )->name('/blog');    
    Route::get ('/blog/{categoria}',                            'BlogController@categoria'          )->name('/blog/categoria');    
    Route::get ('/blog/{tag}/{id}',                             'BlogController@view'               );    
    
    //favoritos e compare
    Route::get ('/favoritos',                                   'PageController@viewFavoritos'      )->name('/favoritos');
        
    //fale conosco
    Route::get ('/contato/fale-conosco',                        'PageController@viewFaleConosco'    )->name('/contato/fale-conosco');
    //trabalhe conosco
    Route::get ('/contato/trabalhe-conosco',                    'PageController@viewTrabalheConosco')->name('/contato/trabalhe-conosco');
    //receba novidades
    Route::get ('/contato/receba-novidades',                    'PageController@viewNewsletter'     )->name('/contato/receba-novidades');
    //venda seu imovel
    Route::get ('/contato/anuncie-seu-imovel',                  'PageController@viewAnuncie'        )->name('/contato/anuncie-seu-imovel');

    
    //Página do Corretor
    Route::get ('/corretores',                                  'CorretorController@index'          );
    Route::get ('/corretores/{slug}',                           'CorretorController@viewCorretor'          );
    
    //corretor
    Route::get ('/corretor/reset',                              'CorretorController@reset'          );
    Route::get ('/corretor/{apelido}',                          'CorretorController@view'           );
    
    //404
    Route::get ('/erro/timeout',                                'PageController@viewTimeout'        );
    
    Route::get ('/seja-nosso-associado',                        'PageController@associado'           );

    //dinamicas
    Route::get ('/{pagina_url}',                                'PageController@viewPage'           );
    
   
});


/* ROTAS PRIVADAS */
Route::group(['middleware' => 'auth'], function () { /**/ });


/* ROTAS PARA API */
Route::group(['prefix' => 'api'], function () { /**/ });



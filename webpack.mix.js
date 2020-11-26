
var mix  = require('laravel-mix');
mix.webpackConfig({ devtool: "source-map" }).sourceMaps();
var browserSync = require('browser-sync').create();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* PROCESSAMENTO DOS ARQUIVOS SASS/CSS */

    /* Processa (no caso apenas copia) os arquivos JS */
        // mix 
        //     .copy('resources/assets/js/controller/busca-avancada.js',   'public/js/controller/busca-avancada.js')
        //     .copy('resources/assets/js/controller/home.js',             'public/js/controller/home.js')
        //     .copy('resources/assets/js/controller/lancamentos.js',      'public/js/controller/lancamentos.js')
        //     .copy('resources/assets/js/controller/prontos.js',          'public/js/controller/prontos.js')
        //     .copy('resources/assets/js/controller/blog.js',             'public/js/controller/blog.js')
        //     .copy('resources/assets/js/custom.js',                      'public/js/custom.js')
        //     .copy('resources/assets/js/app.js',                         'public/js/app.js');

    
            // version() funciona quando usar o mix.js()
            // .version();

        mix
            .minify('public/js/controller/busca-avancada.js')
            .minify('public/js/controller/lancamentos.js')
            .minify('public/js/controller/prontos.js')
            .minify('public/js/controller/blog.js')
            .minify('public/js/custom.js')
            .minify('public/js/smartTracking.js')
            .minify('public/js/app.js');

    /* Combina os arquivos JS em um unico arquivo */
        // var arrJS = [   'public/js/controller/busca-avancada.js'
        //                 ,'public/js/controller/home.js'
        //                 ,'public/js/controller/lancamentos.js'
        //                 ,'public/js/controller/prontos.js'
        //                 ,'public/js/controller/blog.js'
        //                 ,'public/js/custom.js'
        //                 ,'public/js/app.js'
        //             ];
        //mix.scripts( arrJS, 'public/js/all.js');


    /* Processa o SASS em CSS */
        mix 
        .sass('resources/assets/sass/base.scss',    'public/css')
        .sass('resources/assets/sass/theme.scss',  'public/css')
        .sass('resources/assets/sass/land.scss',  'public/css');
        
    /* Combina os arquivos CSS em um unico arquivo */
        // var arrCSS = [  'public/css/base.css'
        //                 ,'public/css/busca.css'
        //                 ,'public/css/footer.css'
        //                 ,'public/css/header.css'
        //                 ,'public/css/pages.css'
        //                 ,'public/css/mobile.css'
        //                 ,'public/css/_theme.css'
        //             ];
        //mix .styles( arrCSS , 'public/css/all.css');


/* Config do browsersync para observar as alteracoes nas views */
    browserSync.init({
        files: [
            'public/css/*.css',
            'public/js/*.js',
            'public/js/*/*.js',
            'resources/views/*.twig',
            'resources/views/**/*.twig'
        ],
        proxy: 'http://localhost:8000'

    });
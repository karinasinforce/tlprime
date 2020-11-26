<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
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

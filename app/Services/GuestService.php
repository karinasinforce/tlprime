<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

//use App\Services\UtilService as UtilService;

class GuestService{
    
    public $token       = "";
    public $email       = "";
    public $nome        = "";
    public $visitados   = array();
    public $favoritos   = array();

    /*
     * Metodo que gera o Hash do Token do visitante / cliente
     * 
     * @param null
     * @return string
     * 
     */
    static public function setHash(){
        $hash = Hash::make(date("Ymdhis"));
        
        return $hash;
    }
    
}

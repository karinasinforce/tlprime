
var _st = (function(){
    var pageUrl = window.location.href;
    var pageTitle = document.title;

    //Recupera o Identificador
    var guid = getCookie('st_user_guid');
    if (!guid) guid = JSON.parse(localStorage.getItem('smartTrack')) ? JSON.parse(localStorage.getItem('smartTrack')).user_guid : create_UUID();
    

    setCookie('st_user_guid', guid);

    var nome = getCookie('st_user_name');
    var email = getCookie('st_user_email');
    var telefone = getCookie('st_user_telefone');
    var celular = getCookie('st_user_celular');
    var buscas = [];
    var update;
    var st_welcome = getBrowserData('st_welcome');

    var smartTrack = getBrowserData('smartTrack');

    if ( smartTrack ) {
        smartTrack = JSON.parse(smartTrack);
    } else {
        smartTrack = {
            'user_guid' : guid,
            'nome': nome,
            'email': email,
            'telefone' : telefone,
            'celular' : celular,
            // 'buscas' : buscas,
            'caminho'   : [],
            'anuncios'  : [],
            // 'bairros'   : [],
            // 'tipos'     : [],
            // 'quartos'   : []
        };
    }

    console.log(smartTrack);

    smartTrack.caminho = registraCaminho(smartTrack.caminho);
    // smartTrack.anuncios = registraAnuncio(smartTrack.anuncios);

    addInputInForms(smartTrack.caminho);

    //Envia os dados para a API
    // if (nome && email && st_welcome) {
    //     update = true;
    //     sendLeadData(smartTrack, update);
    // }

    // transforma em string para colocar no cookie
    smartTrack = JSON.stringify(smartTrack);
    setBrowserData('smartTrack', smartTrack);


    //Functions para registrar cada informação no json e colocar no cookie
    //Captura a Url e o Titulo das páginas visitadas
    function registraCaminho(caminho) {

        var tempJson = {
            'pageTitle' : pageTitle,
            'pageUrl'   : pageUrl
        };

        var ultimaPagina = JSON.stringify(caminho[caminho.length - 1]);
        var tempJsonSting = JSON.stringify(tempJson);

        if ( ultimaPagina !== tempJsonSting ) {
            caminho.push(tempJson);
        }

        // console.log(caminho);

        return caminho;

    }

    //Captura a Url e o Titulo das páginas visitadas
    // function registraAnuncio(anuncios) {
    //     var jsonData = document.getElementById('jsonData');

    //     //Se não tiver o data no meta retorna o que está no cache
    //     if (!jsonData) {
    //         return anuncios;
    //     } else {

    //         jsonData = JSON.parse(jsonData.content);

    //         var ItemId          = jsonData.ID;
    //         var BairroNome      = jsonData.BairroNome;
    //         var BairroID        = jsonData.BairroID;
    //         var QtdQuarto       = jsonData.QtdQuarto;
    //         var CondominioNome  = jsonData.CondominioNome;
    //         var TipoNome        = jsonData.TipoNome;
    //         var Entidade        = jsonData.Codigo ? 'Imovel' : 'Empreendimento';
    //         var Finalidade      = jsonData.ValorLocacao ? 'aluguel' : 'venda';

    //         var tempJson = {
    //             'ItemId'            : ItemId,
    //             'BairroNome'        : BairroNome,
    //             'BairroID'          : BairroID,
    //             'QtdQuarto'         : QtdQuarto,
    //             'CondominioNome'    : CondominioNome,
    //             'TipoNome'          : TipoNome,
    //             'Entidade'          : Entidade,
    //             'Finalidade'        : Finalidade,
    //         };

    //         if (anuncios.length === 0) {
    //             anuncios.push(tempJson);
    //             return anuncios;
    //         } else {

    //             var ultimaAnuncio = JSON.stringify(anuncios[anuncios.length - 1]);
    //             var tempJsonSting = JSON.stringify(tempJson);
    //             if ( ultimaAnuncio !== tempJsonSting ) {
    //                 anuncios.push(tempJson);
    //             }
    //             return anuncios;
    //         }
    //     }


    // }

    function addInputInForms(track) {
        var $form = document.querySelectorAll('form');
        var track = JSON.stringify(track);
        // console.log('track', track);

        $form.forEach(function(el,i){
            if (el.id != 'main-search') {
                // cria o novo input
                var newinput = document.createElement('input');
                // define os atributos do novo input a ser inserido
                newinput.setAttribute('type', 'hidden');
                newinput.setAttribute('name', 'Tracking');
                newinput.setAttribute('data', 'values');
                newinput.setAttribute('class', 'inputTracking');
                newinput.setAttribute('value', track);

                // insere como primeiro item
                var form = $form[i];
                el.insertBefore(newinput, form.childNodes[0]);
            }
        });

    }

    function getBrowserData(key){
        return localStorage.getItem(key);
    }

    function setBrowserData(key, data){
        localStorage.setItem(key, data);
    }

    // function sendLeadData(data, update){
    //     // console.log('data post', data);

    //     var datasend = {
    //         'nome'      : data.nome,
    //         'email'     : data.email,
    //         'guid'      : data.user_guid,
    //         'telefone'  : data.telefone,
    //         'celular'   : data.celular,
    //         'anuncios'  : JSON.stringify(data.anuncios),
    //         'caminho'   : JSON.stringify(data.caminho),
    //     };


    //     var xhr = new XMLHttpRequest();
        
    //     // debugger

    //     if (update) {
    //         xhr.open("put", "http://local.smartmodel/api/track/" + datasend.guid, true);
    //     } else {
    //         xhr.open("post", "http://local.smartmodel/api/track", true);
    //     }

    //     xhr.setRequestHeader("Content-Type", "application/json");

    //     xhr.send(JSON.stringify(datasend));

    //     xhr.onreadystatechange = function() {
    //         if (xhr.readyState === XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
    //             if (xhr.status === 200) {
    //                 console.log('resposta ajax', xhr.responseText);
    //             }
    //             else if (xhr.status === 400) {
    //                 console.log('validação de email - ' + xhr.responseText);
    //             }
    //             else {
    //                 console.log('Ajax error - ' + xhr.responseText);
    //             }
    //         }
    //     };



    // }

    function _st(event, register){
        console.log(event);
        console.log(register);
    }


    function create_UUID(){
        var dt = new Date().getTime();
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = (dt + Math.random()*16)%16 | 0;
            dt = Math.floor(dt/16);
            return (c == 'x' ? r :(r&0x3|0x8)).toString(16);
        });
        return uuid;
    }

    return _st;


})();



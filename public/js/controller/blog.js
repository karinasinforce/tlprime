/* BLOG */ 


function buildBlogList(data, titulo, path_img) {
    
    // registrando helper para usar o slug
    Handlebars.registerHelper('slugfy', function (str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();
    
        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to   = "aaaaeeeeiiiioooouuuunc------";
        for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str;
    })

    Handlebars.registerHelper('date', function (date) {
        var data = new Date(date);
        return data.toLocaleDateString();
    })

    // atualiza a lista de favoritos do box
    var source      = $("#template-list-blog").html();
    var template    = Handlebars.compile(source);
    var html        = template( {"posts": data, "titulo_section": titulo, "path_img": path_img } );

    $("#listNoticies").html(html);

    // initCarousel( $('#owl-home-vitrine-dinamic') );

}


function slugfyPost(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to   = "aaaaeeeeiiiioooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}

$( document ).ready(function() {

});


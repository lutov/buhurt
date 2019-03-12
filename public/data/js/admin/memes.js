$(document).ready(function(){

    var collections = $('#collections');
    $('#name').autocomplete({
        source: "/search/meme_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#genres').autocomplete({
        source: "/search/meme_genre/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "/search/collection_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });

    bind_genres('genres', 'genre');
    bind_genres('collections_list', 'collections');

});
$(document).ready(function(){

    var bands = $('#album_bands');
    var collections = $('#collections');
    // game
    $('#album_name').autocomplete({
        source: "/search/album_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#game_genre').autocomplete({
        source: "/search/album_genre/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bands.autocomplete({
        source: "/search/band_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "/search/collection_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bind_genres('albums_genres', 'album_genre');
    bind_genres('album_bands', 'album_band');
    bind_genres('collections_list', 'collections');

    bands.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});
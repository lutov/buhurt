$(document).ready(function(){

    var bands = $('#album_bands');
    var collections = $('#collections');
    // game
    $('#album_name').autocomplete({
        source: "{!! URL::action('SearchController@album_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#game_genre').autocomplete({
        source: "{!! URL::action('SearchController@album_genre') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bands.autocomplete({
        source: "{!! URL::action('SearchController@band_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
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
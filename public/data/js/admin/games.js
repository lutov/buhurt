$(document).ready(function(){

    var developer = $('#game_developer');
    var publisher = $('#game_publisher');
    var collections = $('#collections');
    // game
    $('#game_name').autocomplete({
        source: "/search/game_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#game_genre').autocomplete({
        source: "/search/game_genre/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#game_platform').autocomplete({
        source: "/search/platform_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    developer.autocomplete({
        source: "/search/company_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    publisher.autocomplete({
        source: "/search/company_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "/search/collection_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bind_genres('games_genres', 'game_genre');
    bind_genres('platforms', 'game_platform');
    bind_genres('collections_list', 'collections');

    developer.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    publisher.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    collections.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});
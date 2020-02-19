$(document).ready(function(){

    var developers = $('#developers');
    var publishers = $('#games_publishers');
    var collections = $('#collections');
    // game
    $('#name').autocomplete({
        source: "/search/game_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#genres').autocomplete({
        source: "/search/game_genre/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#platforms').autocomplete({
        source: "/search/platform_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    developers.autocomplete({
        source: "/search/company_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    publishers.autocomplete({
        source: "/search/company_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "/search/collection_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bind_genres('genres_list', 'genres');
    bind_genres('platforms_list', 'platforms');
    bind_genres('collections_list', 'collections');

    developers.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    publishers.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    collections.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});
$(document).ready(function() {

    var directors = $('#directors');
    var producers = $('#producers');
    var screenwriters = $('#screenwriters');
    var actors = $('#actors');
    var collections = $('#collections');
    // film
    $('#name').autocomplete({
        source: "/search/film_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#genres').autocomplete({
        source: "/search/film_genre/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    directors.autocomplete({
        source: "/search/person_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    producers.autocomplete({
        source: "/search/person_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    screenwriters.autocomplete({
        source: "/search/person_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    actors.autocomplete({
        source: "/search/person_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "/search/collection_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#countries').autocomplete({
        source: "/search/country_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bind_genres('genres_list', 'genres');
    bind_genres('countries_list', 'countries');
    bind_genres('collections_list', 'collections');

    directors.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    producers.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    screenwriters.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    actors.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});

function search_poster() {

    var api_path = '/api/poster/';
    var img_path = '/data/img/posters/';
    var posters = $('#posters');
    var query = $('#poster_query').val();

    posters.html('');
    $.ajax({
        url: api_path,
        data: {query: query},
        success: function(result) {
            //console.log(result)
            //posters.html(result);
            jQuery.each(result, function(i, val) {
                //console.log(val.id);
                var img = '<img class="img-fluid" src="' + img_path + val.id + '.jpg" alt="' + val.name + '" title="' + val.name + '">';
                posters.append(img);
            });
        },
        dataType: 'json'
    });

}
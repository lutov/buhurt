$(document).ready(function() {

    var director = $('#film_director');
    var producer = $('#film_producer');
    var screenwriter = $('#film_screenwriter');
    var actors = $('#film_actors');
    var collections = $('#collections');
    // film
    $('#film_name').autocomplete({
        source: "{!! URL::action('SearchController@film_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#film_genre').autocomplete({
        source: "{!! URL::action('SearchController@film_genre') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    director.autocomplete({
        source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    producer.autocomplete({
        source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    screenwriter.autocomplete({
        source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    actors.autocomplete({
        source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#film_country').autocomplete({
        source: "{!! URL::action('SearchController@country_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bind_genres('films_genres', 'film_genre');
    bind_genres('countries', 'film_country');
    bind_genres('collections_list', 'collections');

    director.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    producer.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    screenwriter.blur(function(event) {
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
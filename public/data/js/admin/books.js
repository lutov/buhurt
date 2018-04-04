$(document).ready(function() {

    var writer = $('#book_writer');
    var publisher = $('#book_publisher');
    var collections = $('#collections');
    // book
    $('#book_name').autocomplete({
        source: "{!! URL::action('SearchController@book_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#book_genre').autocomplete({
        source: "{!! URL::action('SearchController@book_genre') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    writer.autocomplete({
        source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    publisher.autocomplete({
        source: "{!! URL::action('SearchController@company_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    collections.autocomplete({
        source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    bind_genres('books_genres', 'book_genre');
    bind_genres('collections_list', 'collections');

    writer.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    publisher.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    collections.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});
$(document).ready(function() {

    var writers = $('#writers');
    var publishers = $('#books_publishers');
    var collections = $('#collections');
    // book
    $('#name').autocomplete({
        source: "/search/book_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#genres').autocomplete({
        source: "/search/book_genre/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    writers.autocomplete({
        source: "/search/person_name/", // url-адрес
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
    bind_genres('collections_list', 'collections');

    writers.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    publishers.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });
    collections.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});
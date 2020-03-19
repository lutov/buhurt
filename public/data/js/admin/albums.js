$(document).ready(function(){

    var bands = $('#bands');
    var collections = $('#collections');
    // game
    $('#name').autocomplete({
        source: "/search/album_name/", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500
    });
    $('#genres').autocomplete({
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
    bind_genres('genres_list', 'genres');
    bind_genres('collections_list', 'collections');

    bands.blur(function(event) {
        $(this).val($(this).val().replace(/,/g, ';'));
    });

});

function add_track() {
    var block = $('#tracks');
    var element = '<li><input type="text" class="form-control w-100 mb-3" name="tracks[]" placeholder="Трек" value="" /></li>';
    block.append(element);
}
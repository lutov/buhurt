$(function() {

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                // Registration was successful
                //console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                //console.log('ServiceWorker registration failed: ', err);
            });
        });
    }

    $('#search').autocomplete({
        source: "/search/json", // url-адрес
        minLength: 3, // минимальное количество для совершения запроса
        delay: 500,
        select: function (event, ui) {
            $('#search').val(ui.item.value);
            $('#search_form').submit();
        }
    });

    let fastRatingParams = {

        language: 'ru',
        theme: 'krajee-uni',
        size: 'xs',
        emptyStar: '&#9734;',
        filledStar: '&#9733;',
        clearButton: '&#10006;',
        min: 0,
        max: 10,
        step: 1.0,
        stars: '10',
        animate: false,
        showCaption: false,
        showClear: false,
        clearCaption: 'Нет оценки',
        starCaptions: {
            1: 'Очень плохо',
            2: 'Плохо',
            3: 'Посредственно',
            4: 'Ниже среднего',
            5: 'Средне',
            6: 'Выше среднего',
            7: 'Неплохо',
            8: 'Хорошо',
            9: 'Отлично',
            10: 'Великолепно'
        },
        starCaptionClasses: function (val) {
            if (val === null) {
                return 'badge badge-default';
            } else if (val <= 3) {
                return 'badge badge-danger';
            } else if (val <= 5) {
                return 'badge badge-warning';
            } else if (val <= 7) {
                return 'badge badge-primary';
            } else {
                return 'badge badge-success';
            }
        }

    };

    let fastRating = $('.fast_rating');
    if(fastRating) {
        fastRating.rating(fastRatingParams);
        fastRating.on('rating:change', function(event, value, caption) {
            //console.log(this);
            let that = $(this);
            let section = that.data('section');
            let element = that.data('element');
            let path = '/rates/rate/'+section+'/'+element;
            let params = {rate_val: value};
            //console.log(params);
            $.post(path, params, function(data) {
                show_popup(data);
                $.post('/achievements', {}, function(data) {
                    show_popup(data);
                });
            });
        });
    }

    let url = window.location.href;
    if (url.indexOf("#") > 0) {
        var activeTab = url.substring(url.indexOf("#") + 1);
        $('.nav[role="tablist"] a[href="#'+activeTab+'"]').tab('show');
    }

    $('a[role="tab"]').on("click", function() {
        let newUrl;
        const hash = $(this).attr("href");
        newUrl = url.split("#")[0] + hash;
        history.replaceState(null, null, newUrl);
    });

});

/**
 *
 * @param section
 * @param id
 * @param like_class
 * @param liked_class
 */
function set_wanted(section, id, like_class, liked_class) {

    var do_want = $('#want_'+id);

    if(do_want.hasClass(like_class)) {

        var path = '/set_wanted/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {

                do_want.removeClass(like_class);
                do_want.addClass(liked_class);
                //console.log(data);
                showToast(data);

            }
        );

    }

}

/**
 *
 * @param section
 * @param id
 * @param like_class
 * @param liked_class
 */
function unset_wanted(section, id, like_class, liked_class) {

    var do_want = $('#want_'+id);

    if(do_want.hasClass(liked_class)) {

        var path = '/unset_wanted/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {

                do_want.removeClass(liked_class);
                do_want.addClass(like_class);
                //console.log(data);
                showToast(data);

            }
        );

    }

}

/**
 *
 * @param section
 * @param id
 * @param like_class
 * @param liked_class
 */
function set_unwanted(section, id, like_class, liked_class) {

    var do_not_want = $('#not_want_'+id);

    if(do_not_want.hasClass(like_class)) {

        var path = '/set_unwanted/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {

                do_not_want.removeClass(like_class);
                do_not_want.addClass(liked_class);
                //console.log(data);
                showToast(data);

            }
        );

    }

}

/**
 *
 * @param section
 * @param id
 * @param like_class
 * @param liked_class
 */
function unset_unwanted(section, id, like_class, liked_class) {

    var do_not_want = $('#not_want_'+id);

    if(do_not_want.hasClass(liked_class)) {

        var path = '/unset_unwanted/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {

                do_not_want.removeClass(liked_class);
                do_not_want.addClass(like_class);
                //console.log(data);
                showToast(data);

            }
        );

    }

}

function show_comment_form() {var comment_form = $('#comment_form'); comment_form.show(600);}

function comment_add(section, element) {
    var path = '';
    var comment = $('#comment').val();
    //console.log(comment);

    if('' !== comment)
    {
        var id_field = $('#comment_id');
        var id = id_field.val();
        //console.log(id);
        if('' === id) {

            path = '/comment/add';
            $.post(
                path,
                {'comment': comment, 'section': section, 'element': element},
                function (data) {
                    var result = $.parseJSON(data);
                    //console.log(data);
                    $('.comments').prepend(result.comment_text);
                    $('#comment_form').hide(600);
                    //console.log(data);
                }
            );

        } else {

            path = '/comment/edit';
            $.post(
                path,
                {'comment': comment, 'section': section, 'element': element, 'id': id},
                function (data) {
                    var result = $.parseJSON(data);
                    //console.log(data);
                    $('#comment_'+id).replaceWith(result.comment_text);
                    $('#comment_form').hide(600);
                    //console.log(data);
                }
            );

        }
    }
}

/**
 *
 * @param id
 */
function comment_edit(id) {
    let element = $('#comment_'+id+'_text');
    let form = $('#comment');
    let id_field = $('#comment_id');
    let comment_html = element.html();
    let comment = $('<textarea />').html(comment_html).text();
    comment = comment.replace(/<\/?[^>]+>/gi, '');
    id_field.val(id);
    form.val(comment);
    show_comment_form();
}

/**
 *
 * @param id
 */
function comment_delete(id) {

   if(window.confirm('Удалить комментарий?')) {

       var path = '/comment/delete';
       $.post(
           path,
           {'id': id},
           function (data) {
               //var result = $.parseJSON(data);
               //console.log(data);
               //$('.comments').prepend(result.comment_text);
               $('#comment_'+id).hide(600);
               //console.log(data);
           }
       );

   }

}

function expand_genres(text, section) {
    $('#'+section).show(600);
    $(text).removeClass('symlink');
}

function bind_genres(block_id, field_id) {
    jQuery.each($('#'+block_id).children('li'), function (i, val) {
        //console.log(val);
        $(this).addClass('active_text');
        $(this).click(function () {
            var genre = $('#'+field_id);
            //console.log(genre);
            if('' === genre.val()) {
                genre.val($(this).html());
            } else {
                genre.val(genre.val()+'; '+$(this).html());
            }
        });
    });
}

/**
 * @param data
 */
function show_popup(data) {

    //console.log(data);

    var modal_block = $('#modal_block');
    var modal_title = $('#modal_title');
    var modal_content = $('#modal_content');
    var input_message = data.message;
    var delay = 3000;

    var title = data.title;
    modal_title.html(title);

    var output_message = '';
    if('achievement' === data.type) {

        var image_block = '';

        image_block += '<div class="modal_images">';
        for(var i in data.images) {
            if (!data.msg_img.hasOwnProperty(i)) continue;
            image_block += '<img src="/data/img/achievements/'+data.images[i]+'.png" alt="" class="img-fluid">&nbsp;';
        }
        image_block += '</div>';

        output_message += image_block;

    }

    output_message += '<div class="modal_text">'+input_message+'</div>';

    modal_content.html(output_message);
    modal_block.modal();

    if(!data.leave) {
        modal_block.on('shown.bs.modal', function (e) {
            // do something...
            setTimeout(function () {
                modal_block.modal('hide');
            }, delay);
        })
    }

}

/**
 *
 * @param data
 */
function showToast(data) {

    var toastBlock = $('#toast_block');
    var toastTitle = $('#toast_title');
    var toastContent = $('#toast_content');

    var title = data.title;
    toastTitle.html(title);

    var inputMessage = data.message;
    toastContent.html(inputMessage);

    toastBlock.toast('show');

}
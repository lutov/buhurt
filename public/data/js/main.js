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

});

function set_wanted(section, id) {

    var do_want = $('#want_'+id);
    var like_class = 'btn-outline-success';
    var liked_class = 'btn-success';

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

function unset_wanted(section, id) {

    var do_want = $('#want_'+id);
    var like_class = 'btn-outline-success';
    var liked_class = 'btn-success';

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

function set_unwanted(section, id) {

    var do_not_want = $('#not_want_'+id);
    var dislike_class = 'btn-outline-danger';
    var disliked_class = 'btn-danger';

    if(do_not_want.hasClass(dislike_class)) {

        var path = '/set_unwanted/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {

                do_not_want.removeClass(dislike_class);
                do_not_want.addClass(disliked_class);
                //console.log(data);
                showToast(data);

            }
        );

    }

}

function unset_unwanted(section, id) {

    var do_not_want = $('#not_want_'+id);
    var dislike_class = 'btn-outline-danger';
    var disliked_class = 'btn-danger';

    if(do_not_want.hasClass(disliked_class)) {

        var path = '/unset_unwanted/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {

                do_not_want.removeClass(disliked_class);
                do_not_want.addClass(dislike_class);
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


function comment_edit(id) {

    //var parent_element = $('#comment_'+id);
    var element = $('#comment_'+id+'_text');
    var form = $('#comment');
    var id_field = $('#comment_id');
    var comment = element.html();
    comment = comment.replace(/<br>/g, "");

    id_field.val(id);
    form.val(comment);
    show_comment_form();

    //console.log(element);

}

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
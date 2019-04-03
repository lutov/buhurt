$(function() {

    /*
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for(let registration of registrations) {
            registration.unregister()
        } })
    */

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

    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter25959328 = new Ya.Metrika({
                    id:25959328,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-101861790-1', 'auto');
    ga('send', 'pageview');

});

function show_entrance() {
    var entrance_block = $( "#entrance_block" );

    entrance_block.lightbox_me({
        closeSelector: ".close",
        centered: true
    });
}

function show_registration() {
    var registration_block = $( "#registration_block" );

    registration_block.lightbox_me({
        closeSelector: ".close",
        centered: true
    });
}

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

function add_to_list(section, id, list_id) {

    var path = '/lists/add_to_lists/';
    $.post(
        path,
        {
            id: id,
            section: section,
            list_id: list_id
        },
        function (post_data) {
            //console.log(post_data);
        });

}

function remove_from_list(section, id, list_id) {

    var path = '/lists/remove_from_lists/';
    $.post(
        path,
        {
            id: id,
            section: section,
            list_id: list_id
        },
        function (post_data) {
            //console.log(post_data);
        });

}

function toggle_list(section, id, box) {

    var check = $(box);

    //console.log(box.val());

    if(check.prop('checked')) {

        add_to_list(section, id, check.val());

    } else {

        remove_from_list(section, id, check.val());

    }

}

/**
 *
 * @param section
 * @param id
 * @param name
 */
function add_list(section, id) {

    var path = '/lists/add_list/';

    var name = $('#new_list').val();

    $.post(path, {name: name}, function (post_data) {

        lists(section, id);

    });

}

/**
 *
 * @param section
 * @param id
 * @param list_id
 */
function remove_list(section, id, list_id) {

    var path = '/lists/remove_list/';

    $.post(path, {list_id: list_id}, function (post_data) {

        lists(section, id);

    });

}

function lists(section, id) {

    var path = '/lists/get_lists/';
    $.post(
        path,
        {},
        function(get_data) {

            //console.log(get_data);

            var lists = '<form method="POST">';

            jQuery.each(get_data.data, function(key, value) {

                var handler = 'onclick="toggle_list(\''+section+'\', '+id+', this)"';

                lists += '<div>';
                lists += '<input type="checkbox" name="list" id="list_'+value.id+'" value="'+value.id+'" '+handler+'>';
                lists += '&nbsp;';
                lists += '<label for="list_'+value.id+'">';
                lists += value.name;
                lists += '</label>';
                lists += '</div>';

                $.post(
                    '/lists/find_element',
                    {
                        id: id,
                        section: section,
                        list_id: value.id
                    },
                    function(list_data) {if(list_data.id) {$('#list_'+value.id).prop('checked', true);}}
                );

            });

            lists += '</form>';

            lists += '<form method="POST">';
            lists += '<div>';
            lists += '<input type="text" name="new_list" id="new_list" placeholder="Новый список" class="form-control">';
            lists += '<button class="btn btn-outline-primary" type="button" onclick="add_list(\''+section+'\', \''+id+'\')">Создать</button>';
            lists += '</div>';
            lists += '</form>';

            var data = {
                title: 'Добавить в список',
                message: lists,
                leave: true
            };

            show_popup(data);

        }
    );

}

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

function show_section(section)
{
    location.href = '/admin/add/'+section;
}


function expand_genres(text, section)
{
    $('#'+section).show(600);
    $(text).removeClass('symlink');
}


function bind_genres(block_id, field_id)
{
    jQuery.each($('#'+block_id).children('li'), function (i, val) {
        //console.log(val);
        $(this).addClass('active_text');
        $(this).click(function () {
            var genre = $('#'+field_id);
            //console.log(genre);
            if('' === genre.val())
            {
                genre.val($(this).html());
            }
            else
            {
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
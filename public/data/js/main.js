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

function like(section, id) {

    var do_want = $('#want_'+id);
    var like_class = 'btn-outline-success';
    var liked_class = 'btn-success';

    if(do_want.hasClass(like_class))
    {
        var path = '/like/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data) {
                do_want.removeClass(like_class);
                do_want.addClass(liked_class);
                //console.log(data);
                show_popup(JSON.parse(data));
            }
        );
    }

}

function unlike(section, id) {

    var do_want = $('#want_'+id);
    var like_class = 'btn-outline-success';
    var liked_class = 'btn-success';

    if(do_want.hasClass(liked_class))
    {
        var path = '/unlike/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data)
            {
                do_want.removeClass(liked_class);
                do_want.addClass(like_class);
                //console.log(data);
                show_popup(JSON.parse(data));
            }
        );
    }

}

function dislike(section, id) {

    var do_not_want = $('#not_want_'+id);
    var dislike_class = 'btn-outline-danger';
    var disliked_class = 'btn-danger';

    if(do_not_want.hasClass(dislike_class))
    {
        var path = '/dislike/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data)
            {
                do_not_want.removeClass(dislike_class);
                do_not_want.addClass(disliked_class);
                //console.log(data);
                show_popup(JSON.parse(data));
            }
        );
    }

}

function undislike(section, id) {

    var do_not_want = $('#not_want_'+id);
    var dislike_class = 'btn-outline-danger';
    var disliked_class = 'btn-danger';

    if(do_not_want.hasClass(disliked_class))
    {
        var path = '/undislike/'+section+'/'+id;
        $.post(
            path,
            {},
            function(data)
            {
                do_not_want.removeClass(disliked_class);
                do_not_want.addClass(dislike_class);
                //console.log(data);
                show_popup(JSON.parse(data));
            }
        );
    }

}

function scroll_to (elem) {
    $('html, body').animate({ scrollTop: $(elem).offset().top }, 500);
}

function show_comment_form()
{
    var comment_form = $('#comment_form');
    comment_form.show(600);
    scroll_to(comment_form);
}

function comment_add(section, element)
{
    var path = '';
    var comment = $('#comment').val();
    //console.log(comment);

    if('' != comment)
    {
        var id_field = $('#comment_id');
        var id = id_field.val();
        //console.log(id);
        if('' == id) {

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
            if('' == genre.val())
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
function show_popup(data)
{
    var popup = $('#popup');
    var message = data.message;
    var delay = 1500;
    if('achievement' == data.msg_type)
    {
        var images = '';
        for(var i in data.msg_img) {
            if (!data.msg_img.hasOwnProperty(i)) continue;
            images += '<img src="/data/img/achievements/'+data.msg_img[i]+'.png" alt="">&nbsp;';
        }
        message = images+'<p>'+message+'</p>';

        popup.html(message);
        popup.show( "drop", {direction: "down" }, "slow", function() {
            popup.delay(delay).hide("drop", { direction: "down" }, "slow");
        });
    }
    else
    {
        //console.log(data.status);
        popup.html(message);
        popup.show( "drop", {direction: "down" }, "slow", function() {
            popup.delay(delay).hide("drop", { direction: "down" }, "slow");
        });
    }

    //popup.delay(6000).html('');
}
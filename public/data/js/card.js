$(document).ready(function() {

    $('.main_rating').rating({

        //fx: 'full',
        //url: '/rates/rate',

        language: 'ru',
        theme: 'krajee-uni',
        //size: 'xs',
        emptyStar: '&#9734;',
        filledStar: '&#9733;',
        clearButton: '&#10008;',
        min: 0,
        max: 10,
        step: 1.0,
        stars: '10',
        animate: false,
        showCaption: false,
        showClear: false,
        //defaultCaption: 'Нет оценки',
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
            //console.log(val);
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

        /*
        callback: function(responce){
            //this.vote_success.fadeOut(2000);

            $.post('/achievements', {}, function(data) {
                //console.log(data);

                show_popup(data);

            }, 'json');
        }
        */

    });

    /*
    $('#comment_save').click(function(){
        comment_add('{!! $section !!}', '{!! $element->id !!}');
    });
    */

});
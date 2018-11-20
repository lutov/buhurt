$(document).ready(function() {

    var main_rating = $('.main_rating');

    var element_section = $('#element_section');
    var element_id = $('#element_id');

    main_rating.rating({

        language: 'ru',
        theme: 'krajee-uni',
        //size: 'xs',
        emptyStar: '&#9734;',
        filledStar: '&#9733;',
        clearButton: '⊝',
        min: 0,
        max: 10,
        step: 1.0,
        stars: '10',
        animate: false,
        showCaption: false,
        showClear: true,
        //defaultCaption: 'Нет оценки',
        clearCaption: 'Нет оценки',
        clearButtonTitle: 'Удалить оценку',
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

    });

    main_rating.on('rating:change', function(event, value, caption) {

        // console.log(value);
        // console.log(caption);

        var path = '/rates/rate/';

        path += element_section.val();
        path += '/';
        path += element_id.val();

        var params = {
            rate_val: value
        };

        $.post(path, params, function(data) {

            //console.log(data);
            show_popup(data);

        }, 'json');

        $.post('/achievements', {}, function(data) {

            //console.log(data);
            //show_popup(data);

        }, 'json');

    });

    main_rating.on('rating:clear', function(event) {

        // console.log("rating:clear");
        // console.log(caption);

        var path = '/rates/unrate/';

        path += element_section.val();
        path += '/';
        path += element_id.val();

        var params = {};

        $.post(path, params, function(data) {

            //console.log(data);
            show_popup(data);

        }, 'json');

    });

});
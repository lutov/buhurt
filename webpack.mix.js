const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js').extract().version()
    .scripts([
        'public/data/vendor/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.js',
        'public/data/vendor/bootstrap-star-rating/js/star-rating.min.js',
        'public/data/vendor/rangeSlider/ion.rangeSlider-master/js/ion.rangeSlider.min.js',
        'public/data/js/main.js'
    ], 'public/js/main.min.js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .styles([
       'public/data/vendor/bootstrap-star-rating/css/star-rating.min.css',
       'public/data/vendor/rangeSlider/ion.rangeSlider-master/css/ion.rangeSlider.css',
       'public/data/vendor/rangeSlider/ion.rangeSlider-master/css/ion.rangeSlider.skinFlat.css',
       'public/data/css/main.css'
   ], 'public/css/main.min.css');

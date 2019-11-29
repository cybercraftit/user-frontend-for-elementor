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

mix.js('resources/js/app.js', 'assets/js')
    .js('resources/js/editor-app.js', 'assets/js');

mix.less('resources/less/app.less', 'assets/css')
    .less('resources/less/promo.less', 'assets/css');
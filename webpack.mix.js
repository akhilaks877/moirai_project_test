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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
mix.scripts([
    'resources/js/jquery-3.4.1.min.js',
    'resources/js/jquery-ui-1.12.1.js',
    'resources/js/draggable.bundle.js',
    'resources/js/draggable.sortable.js',
    'resources/js/bootstrap-toggle.min.js',
    'resources/js/datatable/jquery.dataTables.min.js',
	'resources/js/datatable/dataTables.bootstrap4.min.js',
    'resources/js/parsley.min.js',
    'resources/js/bootstrap-datepicker.min.js',
    'public/assets/scripts/draggable_backend.js',
    'public/assets/scripts/jquery.dropdown.min.js',
], 'public/js/all.js');

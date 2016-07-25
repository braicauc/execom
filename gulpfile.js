var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function(mix) {
    mix.styles([
        'bootstrap.min.css',
        'font-awesome.css',
        'style.css',
        'easy-autocomplete.css',
        'lightgreen.4.3.1.css',
        'styles.4.3.1.css'
    ],'public/css/all.css');
    mix.version('public/css/all.css');
});



elixir(function(mix) {
    mix.scripts([
        'jquery.1.11.3.js',
        'bootstrap.3.3.5.js',
        'easy-autocomplete.js'
    ],'public/js/all_new.js');
});

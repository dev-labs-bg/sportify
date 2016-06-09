var elixir = require('laravel-elixir');

config.assetsPath = '.';
config.publicPath = '.';

elixir(function(mix) {

    mix
    .sass([

        'bootstrap.scss',
        '../lib/owl.carousel/dist/assets/owl.carousel.min.css',
        '../lib/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        '../lib/chosen/chosen.css',
        'style.scss'

        ],'web/css/style.css')
    .scripts([
        '../lib/jquery/dist/jquery.min.js',
        '../lib/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        '../lib/owl.carousel/dist/owl.carousel.min.js',
        '../lib/chosen/chosen.jquery.js',
        '../lib/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        '../web/js/script.js',
        ],'web/js/all-scripts.js');
});
var elixir = require('laravel-elixir');

config.assetsPath = '.';
config.publicPath = '.';

elixir(function(mix) {

    mix.sass([

        'bootstrap.scss',
        '../lib/owl.carousel/dist/assets/owl.carousel.min.css',
        'style.scss'

        ],'front-end/css/style.css');
});
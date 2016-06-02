var elixir = require('laravel-elixir');

config.assetsPath = '.';
config.publicPath = '.';

elixir(function(mix) {
    mix.sass('_bootstrap.scss','www/front-end/css/bootstrap.css');
});
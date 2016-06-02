var elixir = require('laravel-elixir');

config.assetsPath = '.';
config.publicPath = '.';

elixir(function(mix) {
    mix.sass(['bootstrap.scss','style.scss'],'front-end/css/style.css');
});
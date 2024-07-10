var elixir = require('laravel-elixir');
elixir(function(mix) {
    mix.webpack('./public/js/app.js','./public/js/min/app.min.js')
       .scripts(['./public/js/common_lib.js','./public/js/common.js'],'./public/js/min/app-bundle.min.js')
       .styles(['./public/css/common.css'],'./public/css/min/app-bundle.min.css');
});
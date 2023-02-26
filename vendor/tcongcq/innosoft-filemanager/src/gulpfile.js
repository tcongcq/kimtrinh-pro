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
        "bootstrap.min.css",
        "font-awesome.min.css",
        "toastr.min.css",
        "lightgallery.min.css",
        "nanoscroller.min.css",
        "cropper.min.css",
        "filemanager.min.css"
        
    ], 'public/assets/css/filemanager.css');
    
    mix.scripts([
        "jquery.min.js",
        "bootstrap.min.js",
        "knockout.js",
        "toastr.min.js",
        "lightgallery.js",
        "lg-video.js",
        "lg-thumbnail.js",
        "lg-zoom.js",
        "wavesurfer.min.js",
        "lg-audio.js",
        "jquery.nanoscroller.min.js",
        "cropper.js",
        "filemanager-lang-vi.min.js",
        "filemanager-lang-en.min.js",
        "filemanager.min.js"
        
    ], 'public/assets/js/filemanager.js');
});

elixir.extend('sourcemaps', false);

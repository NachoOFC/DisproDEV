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

// JavaScript compilation
mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/app1.js', 'public/js')
   .vue();

// Sass compilation with Tailwind CSS
mix.sass('resources/sass/app.scss', 'public/css')
   .options({
       processCssUrls: false,
       postCss: [
           require('tailwindcss'),
           require('autoprefixer'),
       ]
   });

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

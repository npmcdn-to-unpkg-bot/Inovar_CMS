var elixir = require('laravel-elixir');
require('elixir-jade');
var gulp = require('gulp');

var cache = require('gulp-cached');
var inject = require('gulp-inject');
var bower = require('gulp-bower');

/*
 |--------------------------------------------------------------------------
 | Caching doc: https://www.npmjs.com/package/gulp-cached
 |--------------------------------------------------------------------------
 */

/*
 |--------------------------------------------------------------------------
 | Inject doc: https://www.npmjs.com/package/gulp-inject/
 |--------------------------------------------------------------------------
 */


/*
 |--------------------------------------------------------------------------
 | System var path
 |--------------------------------------------------------------------------
 */
var bowerDir  = '';// ./assets/vendor
var lessDir   = '';// ./assets/less
var publicCSS = '';// ./assets/css
var publicJS  = '';// ./assets/javascript


/*
 |--------------------------------------------------------------------------
 | Bower setup
 |--------------------------------------------------------------------------
 */
gulp.task('bower', function() {
    return bower({directory:bowerDir, cmd:'update'})
});

/*
 |--------------------------------------------------------------------------
 | Elixir task
 |--------------------------------------------------------------------------
 */

//sourcemaps preferances
elixir.config.sourcemaps = false;

//GULP running task
elixir(function(mix) {

    //JADE settings
    mix.jade({
        baseDir: './',//cartella root di riferimento (plugin,modules,theme/demo)
        dest: '',//cartella di destinazione
        pretty: true,//output minimizzato = false
        search: '**/*.jade', //cerca in tutte le cartella dei file di jade
        src: '/assets/jade/', //cartella dove sono posizionati i file di jade
        extension: '.htm'
    });





    //LESS and JS settings
    //mix.less([ lessDir + '/pages/home.less'], publicCSS + "/home.css");
    mix.less([ lessDir + '/pages/panel.less'], publicCSS + "/panel.css");
    /*
     | Don't edit or remove this comment. It work whit artisan command make:newpublicpage
     */
    mix.scripts(["components/layout.js", "pages/home.js"], publicJS + "/home.min.js");




    //setting Bower resources copy
    //CSS libraries for Control Panel
    mix.styles([
        bowerDir + "/font-icon/icomoon/style.css",
        publicCSS + "/panel.css"
    ], publicCSS + '/panel.min.css');

    //JS libraries for Control Panel
    mix.scripts([
        bowerDir + "/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",
        bowerDir + "/bootstrap-colorpicker/js/bootstrap-colorpicker.js"
    ], publicJS + '/panel.min.js');






});
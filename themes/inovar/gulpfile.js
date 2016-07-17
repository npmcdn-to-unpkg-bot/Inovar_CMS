// Load plugins
var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cached'),
    livereload = require('gulp-livereload'),
    bower = require('gulp-bower'),
    less = require('gulp-less'),
    jade = require('gulp-jade'),
    path = require('path'),
    del = require('del');

livereload({ start: true });

// System var path
var bowerDir  = 'assets/vendor',
    lessDir   = 'assets/less/pages',
    jadeDir   = 'assets/jade',
    publicCSS = 'assets/css',
    publicJS  = 'assets/js';


/*
 |--------------------------------------------------------------------------
 | Inject doc: https://www.npmjs.com/package/gulp-inject/
 |--------------------------------------------------------------------------
 */



/*
 |--------------------------------------------------------------------------
 | Bower task
 |--------------------------------------------------------------------------
 */
gulp.task('bower', function() {
    return bower({directory:bowerDir, cmd:'update'})
});

/*
 |--------------------------------------------------------------------------
 | Clear cache task
 |--------------------------------------------------------------------------
 */
gulp.task('clearcache', function () {
    return cache.caches = {};
});




/*
 |--------------------------------------------------------------------------
 | Jade contents task
 |--------------------------------------------------------------------------
 */
gulp.task('contents', function() {
    return gulp.src(jadeDir+'/content/**/*.jade')
        .pipe(cache('linting'))
        .pipe(jade({
            client: false,
            doctype: 'htm',
            pretty:true
        }))
        .pipe(rename({ extname: ".htm" }))
        .pipe(gulp.dest('content/'))
        .pipe(notify({ message: 'Jade contents compiled' }));
});

/*
 |--------------------------------------------------------------------------
 | Jade layouts task
 |--------------------------------------------------------------------------
 */
gulp.task('layouts', function() {
    return gulp.src(jadeDir+'/layouts/*.jade')
        .pipe(cache('linting'))
        .pipe(jade({
            client: false,
            doctype: 'htm',
            pretty:true
        }))
        .pipe(rename({ extname: ".htm" }))
        .pipe(gulp.dest('layouts/'))
        .pipe(notify({ message: 'Jade layouts compiled' }));
});

/*
 |--------------------------------------------------------------------------
 | Jade pages task
 |--------------------------------------------------------------------------
 */
gulp.task('pages', function() {
    return gulp.src(jadeDir+'/pages/*.jade')
        .pipe(cache('linting'))
        .pipe(jade({
            client: false,
            doctype: 'htm',
            pretty:true
        }))
        .pipe(rename({ extname: ".htm" }))
        .pipe(gulp.dest('pages/'))
        .pipe(notify({ message: 'Jade pages compiled' }));
});

/*
 |--------------------------------------------------------------------------
 | Jade partials task
 |--------------------------------------------------------------------------
 */
gulp.task('partials', function() {
    return gulp.src(jadeDir+'/partials/**/*.jade')
        .pipe(cache('linting'))
        .pipe(jade({
            client: false,
            doctype: 'htm',
            pretty:true
        }))
        .pipe(rename({ extname: ".htm" }))
        .pipe(gulp.dest('partials/'))
        .pipe(notify({ message: 'Jade partials compiled' }));
});


/*
 |--------------------------------------------------------------------------
 | Less task
 |--------------------------------------------------------------------------
 */
gulp.task('less', function() {
    return gulp.src(lessDir+'/**/*.less')
        .pipe(cache('linting'))
        .pipe(less())
        .pipe(autoprefixer('last 2 version'))
        .pipe(gulp.dest(publicCSS))
        .pipe(rename({ suffix: '.min' }))
        .pipe(cssnano())
        .pipe(gulp.dest(publicCSS))
        .pipe(notify({ message: 'Less compiled' }));
});


/*
 |--------------------------------------------------------------------------
 | Script task
 |--------------------------------------------------------------------------
 */
gulp.task('scripts', function() {
    return gulp.src(publicJS+'/dev/**/*.js')
        .pipe(cache('linting'))
        .pipe(gulp.dest(publicJS+'/prod/'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        .pipe(gulp.dest(publicJS+'/prod/'))
        .pipe(notify({ message: 'Scripts task complete' }));
});


/*
 |--------------------------------------------------------------------------
 | Clean task
 |--------------------------------------------------------------------------
 */
gulp.task('clean', function() {
    return del([publicCSS, publicJS+'/prod']);
});


/*
 |--------------------------------------------------------------------------
 | Default task
 |--------------------------------------------------------------------------
 */
gulp.task('default', ['clearcache','clean'], function() {
    gulp.start('less', 'scripts', 'jade');
});


/*
 |--------------------------------------------------------------------------
 | Watch task
 |--------------------------------------------------------------------------
 */
gulp.task('watch', function() {

    // Watch .scss files
    gulp.watch(jadeDir+'/**/*.jade', ['jade']);

    // Watch .scss files
    gulp.watch(lessDir+'/**/*.less', ['less']);

    // Watch .js files
    gulp.watch(publicJS+'/dev/**/*.js', ['scripts']);

    // Create LiveReload server
    livereload.listen();

    // Watch any files in dist/, reload on change
    gulp.watch([publicCSS,publicJS+'/prod/*', jadeDir+'**/*.jade']).on('change', livereload.changed);

});

/*
 |--------------------------------------------------------------------------
 | All Jade task for deploy
 |--------------------------------------------------------------------------
 */
//,'!'+jadeDir+'/content/**/*.jade'
gulp.task('jade', function() {
    return gulp.src(jadeDir+'/**/*.jade')
        .pipe(cache('linting'))
        .pipe(jade({
            client: false,
            doctype: 'htm',
            pretty:true
        }))
        .pipe(rename({ extname: ".htm" }))
        .pipe(gulp.dest(''))
        .pipe(notify({ message: 'Jade compiled' }));
});

/*
 |--------------------------------------------------------------------------
 | Less boostrap task
 |--------------------------------------------------------------------------
 */
var bootstrapless  = 'assets/vendor/bootstrap/less/bootstrap.less',
    bootstrapcss  = 'assets/vendor/bootstrap/dist/css';

gulp.task('bootstrap', function() {
    return gulp.src(bootstrapless)
        .pipe(less())
        .pipe(autoprefixer('last 2 version'))
        .pipe(gulp.dest(bootstrapcss))
        .pipe(rename({ suffix: '.min' }))
        .pipe(cssnano())
        .pipe(gulp.dest(bootstrapcss))
        .pipe(notify({ message: 'Bootstrap library compiled' }));
});
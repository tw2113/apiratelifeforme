var gulp = require('gulp');
var sass = require('gulp-sass');

/*
  Compile SCSS files to CSS
*/
gulp.task('styles', function () {
    return gulp.src('sass/styles.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(gulp.dest('css/'));
});

/*
  Compile the assets
*/
gulp.task('assets', gulp.parallel(
    'styles'
));

/*
  Build
*/
gulp.task('build', gulp.series(
    'assets'
));

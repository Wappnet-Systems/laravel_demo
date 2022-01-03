let gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');

gulp.task('css', function(){
   return gulp.src(['front_asset/assets/css/bootstrap.min.css','front_asset/assets/css/font-awesome.css','front_asset/assets/css/jquery.toast.css'])
   //'front_asset/assets/css/style.css'
   .pipe(concat('styles.css'))
   .pipe(minify())
   .pipe(gulp.dest('dist/css/'));
});


gulp.task('default',gulp.series('css'),function(){
});

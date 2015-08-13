var gulp = require('gulp'),
    gutil = require('gulp-util'),
    rename = require('gulp-rename'),
    coffee = require('gulp-coffee'),
    stripPath = require('./gulp-helpers/strip-path');

var config = {
  temporaryDir: './assets',
  assetsDir:    './web/assets'
};

gulp.task('coffee', function() {
  gulp.src(
      [
        './src/**/Resources/assets/coffee/***.coffee',
        './vendor/sumocoders/**/Resources/assets/coffee/***.coffee'
      ]
  )
      .pipe(coffee({}).on('error', gutil.log))
      .pipe(rename(function(path) {
        var end = path.dirname.indexOf('Bundle') + 6;
        var start = path.dirname.substr(0, end).lastIndexOf('/') + 1;
        var bundle = path.dirname.substr(start, end - start);

        path.dirname = '';
        path.basename = bundle.toLowerCase() + '.' + path.basename;
      }))
      .pipe(gulp.dest(config.assetsDir + '/js'));
});

gulp.task('js', function() {
  gulp.src(
      [
        './src/**/Resources/assets/js/**',
        './vendor/sumocoders/**/Resources/assets/js/**'
      ]
  )
      .pipe(rename(function(path) {
        if (path.extname === '') {
          path.dirname = '';
          path.basename = '';
          return;
        }

        path.dirname = stripPath('/js/', path.dirname);
      }))
      .pipe(gulp.dest(config.assetsDir + '/js'));
});

gulp.task('build', function() {
  gulp.start('coffee', 'js');
});

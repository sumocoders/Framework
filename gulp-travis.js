var gulp = require('gulp'),
    gutil = require('gulp-util'),
    rename = require('gulp-rename'),
    coffee = require('gulp-coffee');

var config = {
  temporaryDir: './assets',
  assetsDir:    './web/assets'
};

var minify = true;

function getStrippedPath(folderToSearch, path) {
  var startOfFolderToSearch = path.indexOf(folderToSearch);

  if (startOfFolderToSearch !== -1) {
    return path.substr(startOfFolderToSearch + folderToSearch.length);
  }

  return '';
}

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

        path.dirname = getStrippedPath('/js/', path.dirname);
      }))
      .pipe(gulp.dest(config.assetsDir + '/js'));
});

gulp.task('build', function() {
  gulp.start('coffee', 'js');
});

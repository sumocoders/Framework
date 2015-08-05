var gulp = require('gulp'),
    gutil = require('gulp-util'),
    rename = require('gulp-rename'),
    coffee = require('gulp-coffee'),
    imagemin = require('gulp-imagemin'),
    fontgen = require('gulp-fontgen'),
    iconfont = require('gulp-iconfont'),
    iconfontcss = require('gulp-iconfont-css'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    gulpSequence = require('gulp-sequence').use(gulp),
    shell = require('gulp-shell'),
    livereload = require('gulp-livereload');

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
function showStatus(task, message, type) {
  message = '--> [' + task + '] ' + message;

  if (type === 'success') {
    message = gutil.colors.green(message);
  }
  else if (type === 'warning') {
    message = gutil.colors.yellow(message);
  }
  else if (type === 'error') {
    message = gutil.colors.red(message);
  }

  gutil.log(message);
}
function showError(error) {
  showStatus('ERROR', error.message, 'error');
}
function handleWatchEvent(event) {
  showStatus('watch', 'File ' + event.path.replace(__dirname, '.') + ' was ' + event.type + '.');
}

gulp.task('coffee', function() {
  gulp.src(
      [
        './src/**/Resources/assets/coffee/***.coffee',
        './vendor/sumocoders/**/Resources/assets/coffee/***.coffee'
      ]
  )
      .pipe(coffee({}).on('error', gutil.log))
      .on('end', function() { showStatus('coffee', 'Coffee-files compiled', 'success')})
      .pipe(rename(function(path) {
        var end = path.dirname.indexOf('Bundle') + 6;
        var start = path.dirname.substr(0, end).lastIndexOf('/') + 1;
        var bundle = path.dirname.substr(start, end - start);

        path.dirname = '';
        path.basename = bundle.toLowerCase() + '.' + path.basename;
      }))
      .on('end', function() { showStatus('coffee', 'Coffee-files renamed', 'success')})
      .pipe(gulp.dest(config.assetsDir + '/js'))
      .on('end', function() { showStatus('coffee', 'Coffee-files saved', 'success')})
      .pipe(livereload());
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
      .on('end', function() { showStatus('js', 'JS-files renamed', 'success')})
      .pipe(gulp.dest(config.assetsDir + '/js'))
      .on('end', function() { showStatus('js', 'JS-files saved', 'success')})
      .pipe(livereload());
});

gulp.task('images', function() {
  gulp.src(
      [
        './src/**/Resources/assets/images/**',
        './vendor/sumocoders/**/Resources/assets/images/**'
      ]
  )
      .pipe(rename(function(path) {
        if (path.extname === '') {
          path.dirname = '';
          path.basename = '';
          return;
        }

        path.dirname = getStrippedPath('/images/', path.dirname);
      }))
      .on('end', function() { showStatus('images', 'image-files renamed', 'success')})
      .pipe(imagemin())
      .on('end', function() { showStatus('images', 'image-files minified', 'success')})
      .pipe(gulp.dest(config.assetsDir + '/images'))
      .on('end', function() { showStatus('images', 'image-files saved', 'success')})
      .pipe(livereload());
});

gulp.task('fonts', gulpSequence(
    'del:cleanup_useless_font_css',
    'fonts:generate'
));

gulp.task('fonts:generate', function() {
  gulp.src(
      [
        './src/**/Resources/assets/fonts/**/*.ttf',
        './src/**/Resources/assets/fonts/**/*.otf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.ttf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.otf'
      ]
  )
      .pipe(rename(function(path) {
        path.dirname = '';
      }))
      .on('end', function() { showStatus('fonts', 'font-files renamed', 'success')})
      .pipe(gulp.dest(config.assetsDir + '/fonts'))
      .on('end', function() { showStatus('fonts', 'font-files saved', 'success')})
      .pipe(fontgen({
        dest: config.assetsDir + '/fonts'
      }))
      .on('end', function() { showStatus('fonts', 'other formats generated', 'success')})
      .pipe(livereload());
});

gulp.task('del:cleanup_useless_font_css', shell.task('rm -rf ' + config.assetsDir + '/fonts/*.css'));

gulp.task('icons', function() {
  gulp.src(
      [
        './src/**/Resources/assets/icon-font/**/*.svg',
        './vendor/sumocoders/**/Resources/assets/icon-font/**/*.svg',
      ]
  )
      .pipe(iconfontcss({
        fontName:   'icons',
        path:       'scss',
        targetPath: '../../../assets/sass/_icons.scss',
        fontPath:   '../fonts/'
      }))
      .on('end', function() { showStatus('icons', 'icon-font SCSS generated', 'success')})
      .pipe(iconfont({
        fontName:  'icons',
        normalize: true
      }))
      .on('end', function() { showStatus('icons', 'icon-font generated', 'success')})
      .pipe(gulp.dest(config.assetsDir + '/fonts'))
      .on('end', function() { showStatus('icons', 'icon-font saved', 'success')})
      .pipe(livereload());
});

gulp.task('sass', ['sass:generate_css']);
gulp.task('sass:centralise_sass_files', ['sass:cleanup'], function() {
  gulp.src(
      [
        './src/**/Resources/assets/sass/**',
        './vendor/sumocoders/**/Resources/assets/sass/**'
      ]
  )
      .pipe(rename(function(path) {
        path.dirname = getStrippedPath('/sass/', path.dirname);
      }))
      .on('end', function() { showStatus('sass', 'scss-files renamed', 'success')})
      .pipe(gulp.dest(config.temporaryDir + '/sass'))
      .on('end', function() { showStatus('sass', 'scss-files saved', 'success')});
});
gulp.task('sass:generate_css', ['sass:centralise_sass_files', 'icons'], function() {
  var outputStyle = 'compressed';
  if (minify === false) {
    outputStyle = 'expanded';
  }

  gulp.src(
      [
        config.temporaryDir + '/sass/style.scss'
      ]
  )
      .pipe(sass({
        includePaths: [
          './web/assets/vendor/bootstrap-sass/assets/stylesheets',
          './web/assets/vendor'
        ],
        outputStyle:  outputStyle

      }).on('error', showError))
      .on('end', function() { showStatus('sass', 'SCSS-files compiled', 'success')})
      .pipe(autoprefixer({}))
      .on('end', function() { showStatus('sass', 'Added prefixes', 'success')})
      .pipe(gulp.dest(config.assetsDir + '/css'))
      .on('end', function() { showStatus('sass', 'CSS-files generated', 'success')})
      .pipe(livereload());
});
gulp.task('sass:cleanup', function() {
  shell([
    'rm -rf ./assets/sass',
    'rm -rf ./web/css'
  ]);
});

gulp.task('translations', ['translations:cleanup'], function() {
  gulp.src(
      [
        './app/Resources/translations/**',
        './src/**/Resources/translations/**',
        './vendor/sumocoders/**/Resources/assets/translations/**'
      ]
  )
      .pipe(livereload());
});
gulp.task('translations:cleanup', function() {
  shell([
    'rm -rf ./app/cache/dev/translation'
  ]);
});

gulp.task('watch', function() {
  livereload.listen();

  gulp.watch(
      [
        './src/**/Resources/assets/coffee/***.coffee',
        './vendor/sumocoders/**/Resources/assets/coffee/***.coffee'
      ],
      ['coffee']
  ).on('change', handleWatchEvent);

  gulp.watch(
      [
        './src/**/Resources/assets/js/**',
        './vendor/sumocoders/**/Resources/assets/js/**'
      ],
      ['js']
  ).on('change', handleWatchEvent);

  gulp.watch(
      [
        './src/**/Resources/assets/images/**',
        './vendor/sumocoders/**/Resources/assets/images/**'
      ],
      ['images']
  ).on('change', handleWatchEvent);

  gulp.watch(
      [
        './src/**/Resources/assets/fonts/**/*.ttf',
        './src/**/Resources/assets/fonts/**/*.otf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.ttf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.otf'
      ],
      ['fonts']
  ).on('change', handleWatchEvent);

  gulp.watch(
      [
        './src/**/Resources/assets/icon-font/**/*.svg',
        './vendor/sumocoders/**/Resources/assets/icon-font/**/*.svg',
        './src/**/Resources/assets/sass/**',
        './vendor/sumocoders/**/Resources/assets/sass/**'
      ],
      ['sass']
  ).on('change', handleWatchEvent);
  gulp.watch(
      [
        './app/Resources/translations/**',
        './src/**/Resources/translations/**',
        './vendor/sumocoders/**/Resources/assets/translations/**'
      ],
      ['translations']
  ).on('change', handleWatchEvent);

});

// public tasks
gulp.task('default', function() {
  gulp.start('build');
});

gulp.task('build', function() {
  gulp.start('coffee', 'js', 'images', 'fonts', 'sass');
});

gulp.task('serve', function() {
  minify = false;
  gulp.start('watch');
});

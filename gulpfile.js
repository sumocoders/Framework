var gulp = require('gulp'),
    gutil = require('gulp-util'),
    gulpif = require('gulp-if'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename'),
    consolidate = require('gulp-consolidate'),
    concat = require('gulp-concat'),
    coffee = require('gulp-coffee'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    fontgen = require('gulp-fontgen'),
    iconfont = require('gulp-iconfont'),
    iconfontcss = require('gulp-iconfont-css'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpSequence = require('gulp-sequence').use(gulp),
    shell = require('gulp-shell'),
    livereload = require('gulp-livereload'),
    parseTwig = require('./gulp-helpers/parse-twig'),
    stripPath = require('./gulp-helpers/strip-path');
const webpackStream = require("webpack-stream");
const webpack = require("webpack");

var config = {
  assetsDir: 'web/assets'
};

var minify = true;

gulp.plumbedSrc = function() {
  return gulp.src.apply(gulp, arguments)
      .pipe(plumber());
};

var commonWebpackConfig = {
  output:  {
    filename: "bundle.js"
  },
  devtool: "source-maps",
  module:  {
    loaders: [
      {
        test:    /.js?$/,
        loader:  "babel",
        exclude: /node_modules/
      }
    ]
  }
};

gulp.task('coffee', function() {
  return gulp.src(
      [
        './app/Resources/assets/coffee/***.coffee',
        './src/**/Resources/assets/coffee/***.coffee',
        './vendor/sumocoders/**/Resources/assets/coffee/***.coffee'
      ]
  )
      .pipe(plumber())
      .pipe(gulpif(minify == false, sourcemaps.init()))
      .pipe(coffee({}).on('error', gutil.log))
      .pipe(rename(function(path) {
        var end = path.dirname.indexOf('Bundle') + 6;
        var start = path.dirname.substr(0, end).lastIndexOf('/') + 1;
        var bundle = path.dirname.substr(start, end - start);

        path.dirname = '';
        path.basename = bundle.toLowerCase() + '.' + path.basename;
      }))
      .pipe(gulpif(minify == false, sourcemaps.write()))
      .pipe(gulp.dest(config.assetsDir + '/js'))
      .pipe(livereload());
});

gulp.task('webpack:generate-production-js', function() {
  return gulp.src('src/**/Resources/assets/js/index.js')
      .pipe(webpackStream(Object.assign({}, commonWebpackConfig, {
        plugins: [
            new webpack.optimize.UglifyJsPlugin({
              compress: {
                warnings: false
              }
            }),
            new webpack.DefinePlugin({
              'process.env.NODE_ENV': '"production"'
            })
        ]
      }, webpack)))
      .pipe(gulp.dest(config.assetsDir + '/js'))
});

gulp.task('js', function() {
  return gulp.src(
      [
        './app/Resources/assets/js/**',
        './src/**/Resources/assets/js/**',
        './vendor/sumocoders/**/Resources/assets/js/**'
      ]
  )
      .pipe(gulpif(minify == false, sourcemaps.init()))
      .pipe(rename(function(path) {
        if (path.extname === '') {
          path.dirname = '';
          path.basename = '';
          return;
        }

        path.dirname = stripPath('/js/', path.dirname);
      }))
      .pipe(gulpif(minify == false, sourcemaps.write()))
      .pipe(gulp.dest(config.assetsDir + '/js'))
      .pipe(livereload());
});

gulp.task('js:concat', ['js', 'coffee'], function() {
  var action = function(grouped) {
    // loop trough all the collected js groups and concat them
    for (var destination in grouped) {
      gulp.src(grouped[destination])
          .pipe(concat(stripPath('/assets/js/', destination)))
          .pipe(uglify())
          .pipe(gulp.dest(config.assetsDir + '/js'));
    }
  }

  return gulp.src([
    './app/Resources/views/**/*.html.twig',
    './src/**/Resources/views/**/*.html.twig',
    '/vendor/sumocoders/**/Resources/views/**/*.html.twig',
  ])
      .pipe(parseTwig(action));
});

gulp.task('images', function() {
  return gulp.src(
      [
        './app/Resources/assets/images/**',
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

        path.dirname = stripPath('/images/', path.dirname);
      }))
      .pipe(imagemin())
      .pipe(gulp.dest(config.assetsDir + '/images'))
      .pipe(livereload());
});

gulp.task('fonts', ['fonts:copy_fonts_from_node_modules'], gulpSequence(
    'del:cleanup_useless_font_css',
    'fonts:generate'
));

gulp.task('fonts:copy_fonts_from_node_modules', function() {
  return gulp.src(
      [
        './node_modules/font-awesome/fonts/*'
      ]
  )
      .pipe(gulp.dest(config.assetsDir + '/fonts'));
});

gulp.task('fonts:generate', function() {
  return gulp.src(
      [
        './app/Resources/assets/fonts/**/*.ttf',
        './app/Resources/assets/fonts/**/*.otf',
        './src/**/Resources/assets/fonts/**/*.ttf',
        './src/**/Resources/assets/fonts/**/*.otf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.ttf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.otf'
      ]
  )
      .pipe(rename(function(path) {
        path.dirname = '';
      }))
      .pipe(gulp.dest(config.assetsDir + '/fonts'))
      .pipe(fontgen({
        dest: config.assetsDir + '/fonts'
      }))
      .pipe(livereload());
});

gulp.task('del:cleanup_useless_font_css', shell.task('rm -rf ' + config.assetsDir + '/fonts/*.css'));

gulp.task('icons', function() {
  return gulp.src(
      [
        './app/Resources/assets/icon-font/**/*.svg',
        './src/**/Resources/assets/icon-font/**/*.svg',
        './vendor/sumocoders/**/Resources/assets/icon-font/**/*.svg',
      ]
  )
      .pipe(iconfont({
        fontName: 'icons',
      }))
      .on('glyphs', function(glyphs) {
        var options = {
          glyphs:    glyphs,
          fontName:  'icons',
          fontPath:  '../fonts/',
          className: 'icon'
        };

        gulp.src('./src/SumoCoders/FrameworkCoreBundle/Resources/assets/sass/base/_iconfont-template.scss')
            .pipe(consolidate('lodash', options))
            .pipe(rename({basename: '_icons'}))
            .pipe(gulp.dest('./src/SumoCoders/FrameworkCoreBundle/Resources/assets/sass/'));
      })
      .pipe(gulp.dest(config.assetsDir + '/fonts'));
});

gulp.task('sass', ['sass:generate_css']);
gulp.task('sass:generate_css', ['icons'], function() {
  return gulp.src(
      [
        './app/Resources/assets/sass/**',
        './src/**/Resources/assets/sass/**',
        './vendor/sumocoders/**/Resources/assets/sass/**'
      ]
  )
      .pipe(gulpif(minify == false, sourcemaps.init()))
      .pipe(plumber())
      .pipe(sass({
        includePaths: [
          './node_modules/bootstrap-sass/assets/stylesheets',
          './node_modules/'
        ],
        outputStyle:  minify ? 'compressed' : 'expanded',
        precision:    10

      }).on('error', gutil.log))
      .pipe(rename(function(path) { path.dirname = ''; }))
      .pipe(autoprefixer({}))
      .pipe(gulpif(minify == false, sourcemaps.write()))
      .pipe(gulp.dest(config.assetsDir + '/css'))
      .pipe(livereload());
});
gulp.task('sass:cleanup', ['sass'], shell.task([
      'rm src/SumoCoders/FrameworkCoreBundle/Resources/assets/sass/_icons.scss'
    ])
);

gulp.task('translations', ['translations:cleanup'], function() {
  return gulp.src(
      [
        './app/Resources/translations/**',
        './src/**/Resources/translations/**',
        './vendor/sumocoders/**/Resources/assets/translations/**'
      ]
  )
      .pipe(livereload());
});
gulp.task('translations:cleanup', shell.task([
      'rm -rf ./app/cache/dev/translations'
    ])
);

gulp.task('watch', [], function() {
  minify = false;

  livereload.listen();

  gulp.watch(
      [
        './app/Resources/assets/coffee/***.coffee',
        './src/**/Resources/assets/coffee/***.coffee',
        './vendor/sumocoders/**/Resources/assets/coffee/***.coffee'
      ],
      ['coffee']
  );

  gulp.watch(
      [
        './app/Resources/assets/js/**',
        './src/**/Resources/assets/js/**',
        './vendor/sumocoders/**/Resources/assets/js/**'
      ],
      ['js']
  );

  gulp.watch(
      [
        './app/Resources/views/**/*.html.twig',
        './src/**/Resources/views/**/*.html.twig',
        '/vendor/sumocoders/**/Resources/views/**/*.html.twig',
      ],
      ['js:concat']
  );

  gulp.watch(
      [
        './app/Resources/assets/images/**',
        './src/**/Resources/assets/images/**',
        './vendor/sumocoders/**/Resources/assets/images/**'
      ],
      ['images']
  );

  gulp.watch(
      [
        './app/Resources/assets/fonts/**/*.ttf',
        './app/Resources/assets/fonts/**/*.otf',
        './src/**/Resources/assets/fonts/**/*.ttf',
        './src/**/Resources/assets/fonts/**/*.otf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.ttf',
        './vendor/sumocoders/**/Resources/assets/fonts/**/*.otf'
      ],
      ['fonts']
  );

  gulp.watch(
      [
        './app/Resources/assets/icon-font/**/*.svg',
        './src/**/Resources/assets/icon-font/**/*.svg',
        './vendor/sumocoders/**/Resources/assets/icon-font/**/*.svg',
        './app/Resources/assets/sass/**',
        './src/**/Resources/assets/sass/**',
        './vendor/sumocoders/**/Resources/assets/sass/**'
      ],
      ['sass', 'sass:cleanup']
  );
  gulp.watch(
      [
        './app/Resources/translations/**',
        './src/**/Resources/translations/**',
        './vendor/sumocoders/**/Resources/assets/translations/**'
      ],
      ['translations']
  );

});

// public tasks
gulp.task('default', function() {
  gulp.start('build');
});

gulp.task('build', function() {
  gulp.start(
      'coffee',
      'js',
      'js:concat',
      'images',
      'fonts',
      'sass',
      'sass:cleanup'
  );
});

gulp.task('serve', function() {
  gulp.start('watch');
});

module.exports = (grunt) ->
  # Project configuration
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'
    assetsPath: 'assets'
    webAssetsPath: 'web/assets'

    # Concurrent configuration
    concurrent:
      syncFiles: [
        'sync:coffee'
        'sync:fonts'
        'sync:images'
        'sync:js'
        'sync:sass'
      ]
      syncImagesAndJs: [
        'sync:images'
        'sync:js'
      ]
      generateAssets: [
        'generateFonts'
        'generateCssForProduction'
        'generateImages'
        'grunticon'
        'generateJs'
        'generateAsseticAssets'
      ]
      watch:
        options:
          logConcurrentOutput: true
        tasks: [
          'watch'
        ]

    # Sass configuration
    sass:
      dev:
        options:
          outputStyle: 'expanded'
          lineNumbers: true
          includePaths: [
            '<%= webAssetsPath %>/vendor/bootstrap-sass/assets/stylesheets'
          ]
        files: [
          expand: true
          cwd: '<%= assetsPath %>/sass'
          src: ['*.scss']
          dest: '<%= webAssetsPath %>/css'
          ext: '.css'
        ]
      dist:
        options:
          outputStyle: 'compressed'
        files: [
          expand: true
          cwd: '<%= assetsPath %>/sass'
          src: ['*.scss']
          dest: '<%= webAssetsPath %>/css'
          ext: '.css'
        ]

    # Coffee configuration
    coffee:
      dev:
        expand: true
        flatten: true
        src: [
          '<%= assetsPath %>/coffee/*.coffee'
          '<%= assetsPath %>/coffee/**/*.coffee'
        ],
        dest: '<%= webAssetsPath %>/js/'
        rename: (dest, src) ->
          dest + src.replace('.coffee', '.js')

    # Fontgen configuration
    fontgen:
      all:
        files: [
          expand: true
          src: [
            '<%= assetsPath %>/fonts/**/*.ttf'
            '<%= assetsPath %>/fonts/**/*.eot'
          ]
          dest: '<%= webAssetsPath %>/fonts/'
          rename: (dest, src) ->
            uniqueFolderToSearchFor = '/fonts/'
            startOfFontsDir = src.indexOf(uniqueFolderToSearchFor)
            filename = src.replace(/^.*[\\\/]/, '')
            dest + src.replace(filename, '').substr(startOfFontsDir + uniqueFolderToSearchFor.length)
        ]

    # Autoprefixer configuration
    autoprefixer:
      all:
        src: '<%= webAssetsPath %>/css/*.css'

    # Clean configuration
    clean:
      afterFontgen: [
        '<%= webAssetsPath %>/fonts/*.css'  # remove all generated css-files as they are obsolete
      ]
      afterGenerateCss: [
        '.sass-cache'
      ]
      beforeAsseticDump: [
        'web/js/*bundle*.js'
      ]
      removeSymfonyCache: [
        'app/cache/*'
      ]

    # Copy configuration
    copy:
      glyphiconsWoff2:
        expand: true
        flatten: true
        src: '<%= webAssetsPath %>/vendor/bootstrap/fonts/*.woff2'
        dest: '<%= webAssetsPath %>/fonts/bootstrap/'
      jQuerySourceMap:
        src: '<%= webAssetsPath %>/vendor/jquery/dist/jquery.min.map'
        dest: 'web/js/jquery.min.map'

    # Imagemin configuration
    imagemin:
      production:
        files: [
          expand: true
          cwd: '<%= webAssetsPath %>/images/'
          src: ['**.{png,jpg,gif,jpeg}']
          dest: '<%= webAssetsPath %>/images/'
        ]

    # Grunticon configuration
    grunticon:
      myIcons:
        files: [
          expand: true
          src: [
            'src/**/Resources/assets/icons/*.svg'
            'src/**/Resources/assets/icons/*.png'
          ]
          dest: '<%= webAssetsPath %>/icons/'
        ]

    # Shell config
    shell:
      options:
        stdout: true
      clearCache:
        command: 'app/console cache:clear'
      asseticDump:
        command: 'app/console assetic:dump'
      asseticWatch:
        command: 'app/console assetic:watch'
      assetsInstall:
        command: 'app/console assets:install'

    # Sync configuration
    sync:
      coffee:
        expand: true
        updateAndDelete: true
        dest: '<%= assetsPath %>/coffee/'
        filter: 'isFile'
        src:  [
          'src/**/Resources/assets/coffee/**'
        ]
        rename: (dest, src) ->
          chunks = src.split('/')
          newPathName = chunks[chunks.length - 1]

          # prepend bundle if needed
          for chunk in chunks
            index = chunk.indexOf('Bundle')
            if index != -1 && index != 0
              newPathName = chunk.toLowerCase() + '.' + newPathName

          dest + newPathName
      fonts:
        expand: true
        updateAndDelete: true
        dest: '<%= assetsPath %>/fonts/'
        src:  [
          'src/**/Resources/assets/fonts/**/*.ttf'
          'src/**/Resources/assets/fonts/**/*.otf'
        ]
        rename: (dest, src) ->
          uniqueFolderToSearchFor = '/fonts/'
          startOfFontsDir = src.indexOf(uniqueFolderToSearchFor)
          dest + src.substr(startOfFontsDir + uniqueFolderToSearchFor.length);
      images:
        expand: true
        updateAndDelete: true
        dest: '<%= webAssetsPath %>/images/'
        filter: 'isFile'
        src:  [
          'src/**/Resources/assets/images/**'
        ]
        rename: (dest, src) ->
          uniqueFolderToSearchFor = '/images/'
          startOfImagesDir = src.indexOf(uniqueFolderToSearchFor)
          dest + src.substr(startOfImagesDir + uniqueFolderToSearchFor.length);
      js:
        expand: true
        ignoreInDest: '*bundle*.js'
        updateAndDelete: false
        dest: '<%= webAssetsPath %>/js/'
        filter: 'isFile'
        src:  [
          'src/**/Resources/assets/js/**'
        ]
        rename: (dest, src) ->
          uniqueFolderToSearchFor = '/js/'
          startOfImagesDir = src.indexOf(uniqueFolderToSearchFor)
          dest + src.substr(startOfImagesDir + uniqueFolderToSearchFor.length);
      sass:
        expand: true
        updateAndDelete: true
        dest: '<%= assetsPath %>/sass/'
        filter: 'isFile'
        src:  [
          'src/**/Resources/assets/sass/**'
        ]
        rename: (dest, src) ->
          uniqueFolderToSearchFor = '/sass/'
          startOfImagesDir = src.indexOf(uniqueFolderToSearchFor)
          dest + src.substr(startOfImagesDir + uniqueFolderToSearchFor.length);

    # Watch configuration
    watch:
      # Watch the coffee files so we can (re)generate the js files
      coffee:
        files: [
          '<%= assetsPath %>/coffee/**'
          'src/**/Resources/assets/coffee/**'
        ]
        tasks: [
          'sync:coffee'
          'generateJs'
        ]
        options:
          livereload: true
      # Watch the files to be copied
      copy:
        files: [
          'src/**/Resources/assets/images/**'
          'src/**/Resources/assets/js/**'
        ]
        tasks: [
          'concurrent:syncImagesAndJs'
        ]
        options:
          livereload: true
      # Watch the font folders so we can (re)generate the fonts
      fonts:
        files: [
          'src/**/Resources/assets/fonts/**'
        ]
        tasks: [
          'generateFonts'
        ]
        options:
          livereload: true
      # Watch the icon files so we can (re)generate the iconfont
      icons:
        files: [
          'src/**/Resources/assets/icons/**'
        ]
        tasks: [
          'grunticon'
        ]
        options:
          livereload: true
      # Watch the sass files so we can (re)generate the css files
      sass:
        files: [
          '<%= assetsPath %>/sass/**'
          'src/**/Resources/assets/sass/**'
        ]
        tasks: [
          'sync:sass'
          'sass:dev'
          'autoprefixer'
        ]
        options:
          livereload: true

  # Load all grunt tasks
  require('load-grunt-tasks')(grunt);

  # Generate all needed files for the fonts and do some cleanup
  grunt.registerTask 'generateFonts', [
    'fontgen'
    'copy:glyphiconsWoff2'
    'clean:afterFontgen'
  ]

  # Generate the css-files
  grunt.registerTask 'generateCssForDev', [
    'sass:dev'
    'autoprefixer'
    'clean:afterGenerateCss'
  ]

  # Generate the css-files for live
  grunt.registerTask 'generateCssForProduction', [
    'sass:dist'
    'autoprefixer'
    'clean:afterGenerateCss'
  ]

  # Generate the js files
  grunt.registerTask 'generateJs', [
    'coffee'
    'copy:jQuerySourceMap'
    'clean:beforeAsseticDump'
    'shell:asseticDump'
  ]

  # Generate images
  grunt.registerTask 'generateImages', [
    'imagemin'
  ]

  # Do Assetic stuff
  grunt.registerTask 'generateAsseticAssets', [
    'clean:removeSymfonyCache'
    'shell:clearCache'
    'clean:beforeAsseticDump'
    'shell:asseticDump'
    'shell:assetsInstall'
    'shell:clearCache'
    'clean:removeSymfonyCache'
  ]

  # Default task
  grunt.registerTask 'default', [
    'serve'
  ]

  grunt.registerTask 'serve', [
    'concurrent:watch'
  ]

  # Production task
  grunt.registerTask 'build', [
    'concurrent:syncFiles'
    'concurrent:generateAssets'
  ]

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
        'generateIconFonts'
        'generateJs'
        'generateAsseticAssets'
      ]
      watch:
        options:
          logConcurrentOutput: true
        tasks: [
          'watch'
        ]

    # Compass configuration
    compass:
      options:
        require: [
          'compass_sumo'
        ]
        cssDir: '<%= webAssetsPath %>/css'
        fontsDir: '<%= webAssetsPath %>/fonts'
        imagesDir: '<%= webAssetsPath %>/images'
        sassDir: '<%= assetsPath %>/sass'
        relativeAssets: true
        bundleExec: true
        outputStyle: 'expanded'
        noLineComments: false
      dev: {}
      production:
        options:
          outputStyle: 'compressed'
          noLineComments: true

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
      beforeGenerateIconFonts: [
        '<%= assetsPath %>/sass/_icons.scss'
        '<%= assetsPath %>/sass/_icon-*.scss'
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

    # Webfont configuration
    webfont:
      all:
        src:  [
          'src/**/Resources/assets/icons/*'
        ]
        dest: '<%= webAssetsPath %>/fonts/'
        destCss: '<%= assetsPath %>/sass/'
        classPrefix: 'icon-'
        options:
          stylesheet: 'scss'
          htmlDemo: false
          template: 'grunt/src/webfont/templates/template.scss'
          templateOptions:
            classPrefix: 'icon-'

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
            if chunk.indexOf('Bundle') != -1
              newPathName = chunk.toLowerCase() + '.' + newPathName

          dest + newPathName
      fonts:
        expand: true
        updateAndDelete: true
        dest: '<%= assetsPath %>/fonts/'
        src:  [
          'src/**/Resources/assets/fonts/**/*.ttf'
          'src/**/Resources/assets/fonts/**/*.oft'
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
          'generateIconFonts'
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
          'compass:dev'
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

  grunt.registerTask 'generateIconFonts', [
    'clean:beforeGenerateIconFonts'
    'webfont'
  ]

  # Generate the css-files
  grunt.registerTask 'generateCssForDev', [
    'compass'
    'autoprefixer'
    'clean:afterGenerateCss'
  ]

  # Generate the css-files for live
  grunt.registerTask 'generateCssForProduction', [
    'compass:production'
    'autoprefixer'
    'clean:afterGenerateCss'
  ]

  # Generate the js files
  grunt.registerTask 'generateJs', [
    'coffee'
    'copy:jQuerySourceMap'
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

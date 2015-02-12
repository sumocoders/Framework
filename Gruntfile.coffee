module.exports = (grunt) ->
  # Project configuration
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'
    assetsPath: 'assets'
    webAssetsPath: 'web/assets'

    # Concurrent configuration
    concurrent:
      copyfiles: [
        'copy:coffee'
        'copy:fonts'
        'copy:images'
        'copy:js'
        'copy:sass'
      ]
      generateAssets: [
        'generateFonts'
        'generateCssForProduction'
        'generateImages'
        'generateIconFonts'
        'generateJs'
      ]

    # Copy configuration
    copy:
      fonts:
        expand: true
        flatten: true
        dest: '<%= assetsPath %>/fonts/'
        src:  [
          'src/**/Resources/assets/fonts/**.ttf'
          'src/**/Resources/assets/fonts/**.oft'
        ]
      images:
        expand: true
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
        dest: '<%= assetsPath %>/sass/'
        filter: 'isFile'
        src:  [
          'src/**/Resources/assets/sass/**'
        ]
        rename: (dest, src) ->
          uniqueFolderToSearchFor = '/sass/'
          startOfImagesDir = src.indexOf(uniqueFolderToSearchFor)
          dest + src.substr(startOfImagesDir + uniqueFolderToSearchFor.length);
      coffee:
        expand: true
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
      prod:
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
          src: [
            '<%= assetsPath %>/fonts/*.ttf'
            '<%= assetsPath %>/fonts/*.eot'
          ]
          dest: '<%= webAssetsPath %>/fonts/'
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
      afterGenerateCssForProduction: [
        '.sass-cache'
      ]
      beforeGenerateIconFonts: [
        '<%= assetsPath %>/sass/_icons.scss'
        '<%= assetsPath %>/sass/_icon-*.scss'
      ]

    # Imagemin configuration
    imagemin:
      prod:
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

    # Watch configuration
    watch:
      # Watch the coffee files so we can (re)generate the js files
      coffee:
        files: [
          '<%= assetsPath %>/coffee/**'
          'src/**/Resources/assets/coffee/**'
        ]
        tasks: [
          'copy:coffee'
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
          'concurrent:copyfiles'
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
          'copy:sass'
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
    'clean:afterFontgen'
  ]

  grunt.registerTask 'generateIconFonts', [
    'clean:beforeGenerateIconFonts'
    'webfont'
  ]

  # Generate the css-files
  grunt.registerTask 'generateCssForProduction', [
    'compass:prod'
    'autoprefixer'
    'clean:afterGenerateCssForProduction'
  ]

  # Generate the js files
  grunt.registerTask 'generateJs', [
    'coffee'
  ]

  # Generate images
  grunt.registerTask 'generateImages', [
    'imagemin'
  ]

  # Default task
  grunt.registerTask 'default', [
    'watch'
  ]

  # Production task
  grunt.registerTask 'build', [
    'concurrent:copyfiles'
    'concurrent:generateAssets'
  ]

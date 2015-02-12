module.exports = (grunt) ->
  # Project configuration
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'
    assetsPath: 'assets'
    webAssetsPath: 'web/assets'

    # Copy configuration
    copy:
      fonts:
        expand: true
        flatten: true
        dest: '<%= assetsPath %>/fonts/'
        src:  [
          'src/**/Resources/assets/fonts/*'
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

    autoprefixer:
      all:
        src: '<%= webAssetsPath %>/css/*.css'

    # Clean configuration
    clean:
      afterFontgen: [
        '<%= webAssetsPath %>/fonts/*.css'  # remove all generated css-files as they are obsolete
      ]

    # Watch configuration
    watch:
      # Watch the coffee files so we can (re)generate the js files
      coffee:
        files: [
          '<%= assetsPath %>/coffee/**'
        ]
        tasks: [
          'coffee'
        ]
      # Watch the files to be copied
      copy:
        files: [
          'src/**/Resources/assets/coffee/**'
          'src/**/Resources/assets/images/**'
          'src/**/Resources/assets/js/**'
          'src/**/Resources/assets/sass/**'
        ]
        tasks: [
          'copyfiles'
        ]
      # Watch the font folders so we can (re)generate the fonts
      fonts:
        file: [
          'src/**/Resources/assets/fonts/**'
        ]
        tasks: [
          'generateFonts'
        ]
      # Watch the sass files so we can (re)generate the css files
      sass:
        files: [
          '<%= assetsPath %>/sass/**'
        ]
        tasks: [
          'compass:dev'
          'autoprefixer'
        ]

  # Load all grunt tasks
  require('load-grunt-tasks')(grunt);

  # Copy the files into the correct folders
  grunt.registerTask 'copyfiles', [
    'copy:coffee'
    'copy:images'
    'copy:js'
    'copy:sass'
  ]

  # Generate all needed files for the fonts and do some cleanup
  grunt.registerTask 'generateFonts', [
    'copy:fonts'
    'fontgen'
    'clean:afterFontgen'
  ]

  # Generate the js-files

  # Default task
  grunt.registerTask 'default', [
    'watch'
  ]

  # Production task
  grunt.registerTask 'build', [
    'copyfiles'
    'generateFonts'
    'compass:prod'
    'autoprefixer'
    'coffee'
  ]

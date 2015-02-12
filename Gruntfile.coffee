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

    # Watch configuration
    watch:
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
      fonts:
        file: [
          'src/**/Resources/assets/fonts/**'
        ]
        tasks: [
          'generateFonts'
        ]
      sass:
        files: [
          '<%= assetsPath %>/sass/**'
        ]
        tasks: [
          'compass:dev'
        ]

  # Load all grunt tasks
  require('load-grunt-tasks')(grunt);

  # Private task(s)
  grunt.registerTask 'copyfiles', [
    'copy:fonts'
    'copy:js'
    'copy:images'
    'copy:sass'
  ]

  grunt.registerTask 'generateFonts', [
    'copy:fonts'
    'fontgen'
  ]

  # Default task(s)
  grunt.registerTask 'default', [
    'watch'
  ]

  # Production task(s)
  grunt.registerTask 'build', [
    'copyfiles'
    'generateFonts'
    'compass:prod'
    'coffee'
  ]

module.exports = (grunt) ->
  # Project configuration
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'
    assetsPath: 'assets'

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
        dest: '<%= assetsPath %>/images/'
        filter: 'isFile'
        src:  [
          'src/**/Resources/assets/images/**'
        ]
        rename: (dest, src) ->
          uniqueFolderToSearchFor = '/images/'
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

    # Compass configuration
    compass:
      options:
        require: [
          'compass_sumo'
        ]
        cssDir: '<%= assetsPath %>/css'
        fontsDir: '<%= assetsPath %>/fonts'
        imagesDir: '<%= assetsPath %>/images'
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

    # Watch configuration
    watch:
      copy:
        files: [
          'src/**/Resources/assets/fonts/*',
          'src/**/Resources/assets/images/**',
          'src/**/Resources/assets/sass/**'
        ]
        tasks: [
          'copyfiles'
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
    'copy:fonts',
    'copy:images',
    'copy:sass',
  ]

  # Default task(s)
  grunt.registerTask 'default', [
    'watch'
  ]

  # Production task(s)
  grunt.registerTask 'build', [
    'copyfiles'
    'compass:prod'
  ]

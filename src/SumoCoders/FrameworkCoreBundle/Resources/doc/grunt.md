# Using grunt

We are using grunt to handle a shitload of frontend related tasks, below you 
can find all stuff that is done. This is more a guide for you to understand 
what is happening.


### Coffee

We will look into all bundles in the `src`-folder for all files in the 
`Resources/assets/coffee`-folder, these files will be copied to the 
`assets/coffee`-folder in the root directory. All files will be prefixed with 
the lowercased bundlename.

After that the files will be compiled into `web/assets/js`.

#### @todo

[ ] add documentation for usage in twig


### Fonts

We will look into all bundles in the `src`-folder for all the ttf and oft-files
in the `Resources/assets/fonts`-folder, these files will be copied to 
`assets/fonts`-folder in the root directory. When this is done all the other 
formats (eot, svg, ttf, woff) are created in the `web/assets/fonts`-folder.

You can link to the font-files with the compass-shortcurt font-dir('filename') 
in your scss-files.


### Icons

We will look into all bundles in the `src`-folder for all the svg-files in the 
`Resources/assets/icons`-folder, these files will be compiled into an iconfont 
located in `web/assets/fonts`-folder in the root directory. A _icons.scss-file 
will be stored in the `assets/sass`-folder.


### Images

We will look into all bundles in the `src`-folder for all files in the 
`Resources/assets/images`-folder, these files will be copied to the 
`web/assets/images`-folder in the root directory. The folder structure you 
(optionally) created will be preserved. 

*Important*: make sure you don't have duplicate filenames as the files will be 
overwritten.

You can link to the font-files with the compass-shortcurt image-dir('filename')
in your scss-files.

When running `grunt build` the images will be optimized for web with the 
[grunt-contrib-imagemin-plugin](https://www.npmjs.com/package/grunt-contrib-imagemin).

#### @todo

[ ] add documentation for usage in twig


### JS

We will look into all bundles in the `src`-folder for all files in the 
`Resources/assets/js`-folder, these files will be copied to the 
`web/assets/js`-folder in the root directory. The folder structure you 
(optionally) created will be preserved.

#### @todo

[ ] add documentation for usage in twig


### SASS/SCSS

We will look into all bundles in the `src`-folder for all files in the 
`Resources/assets/sass`-folder, these files will be copied to the 
`assets/sass`-folder in the root directory. The folder structure you 
(optionally) created will be preserved.

After that the files will be compiled into `web/assets/css`. When running 
`grunt build` the files will be compiled as minified as possible, so without 
any comments.

When the sass/scss-files are compiled we use the 
[grunt-autoprefixer-plugin](https://www.npmjs.com/package/grunt-autoprefixer)

#### @todo

[ ] add documentation for usage in twig


## Usage

### While developing

    grunt
    
We have implemented live-reload, so your changes will be reloaded in the 
browser. This will only happen in the dev-environment.

### Before launching your website

    grunt build
    
You don't have to bother if you are using deployment as we will handle it for you.

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

As this will generate seperate files for each file we will combine them through
assetic. See [How to use Assetic for asset management](http://symfony.com/doc/current/cookbook/assetic/asset_management.html)
for the full documentation but basically:

    {% javascripts
        'assets/js/frameworkcorebundle.framework.data.js'
        'assets/js/frameworkcorebundle.framework.locale.js'
        'assets/js/frameworkcorebundle.framework.form.js'
        'assets/js/frameworkcorebundle.framework.js'
        'assets/js/frameworkcorebundle.framework.form.search.js'
        'assets/js/frameworkcorebundle.app.js'
        output='js/app.js'
    %}
        <script src="{{ asset_url }}"></script>
    {% endjavascripts %}


### Fonts

We will look into all bundles in the `src`-folder for all the ttf and otf-files
in the `Resources/assets/fonts`-folder, these files will be copied to 
`assets/fonts`-folder in the root directory. When this is done all the other 
formats (eot, svg, ttf, woff) are created in the `web/assets/fonts`-folder.

You can link to the font-files with the compass-shortcurt font-url('filename') 
in your scss-files.


### Icons

We will look into all bundle in the `src` folder for svg files in the
`Resources/assets/icon-font` folder. These svg icons will be embedded in a
CSS file which will be asynchronously loaded into the web page. If the browser
doesn't support SVG, another CSS file with PNG fallbacks will be loaded.
All of this happens through [grunticon](https://github.com/filamentgroup/grunticon).
The class names are automatically generated based on the file name of the icon.
For example, the icon with the name `arrow.svg` will generate a CSS class
with the name `icon-arrow`. To keep the class names consistent, always use
dashes in the file names, no underscores.

Icons can be styled with CSS but can not be extended with Sass, because 
the CSS with the icons gets loaded asynchronously for performance reasons.

More possibilities of grunticon include inline embedding of icons, creating 
color variations of the same icon and recoloring certain parts of icons.
Please refer to the [plugin's documentation](https://github.com/filamentgroup/grunticon)
for more info


### Images

We will look into all bundles in the `src`-folder for all files in the 
`Resources/assets/images`-folder, these files will be copied to the 
`web/assets/images`-folder in the root directory. The folder structure you 
(optionally) created will be preserved. 

*Important*: make sure you don't have duplicate filenames as the files will be 
overwritten.

You can link to the font-files with the compass-shortcurt image-url('filename')`
in your scss-files.

When running `grunt build` the images will be optimized for web with the 
[grunt-contrib-imagemin-plugin](https://www.npmjs.com/package/grunt-contrib-imagemin).

You can use the `asset`-method in twig templates like below:

    <img src="{{ asset('assets/images/arrow_show_menu.png') }}" />


### JS

We will look into all bundles in the `src`-folder for all files in the 
`Resources/assets/js`-folder, these files will be copied to the 
`web/assets/js`-folder in the root directory. The folder structure you 
(optionally) created will be preserved.

You can use the `asset`-method in twig templates like below:

    <img src="{{ asset('assets/js/sumo_plugins.js') }}" />


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

If you want you can combine multiple generated css files into one by using 
Assetic:

    {% stylesheets
        'assets/css/1.css'
        'assets/css/2.css'
        filter='cssrewrite'
        output='css/combined.css'
    %}
        <link rel="stylesheet" href="{{ asset_url }}" />
    {% endstylesheets %}

But in most cases you will just use

    {% stylesheets
        'assets/css/style.css'
        filter='cssrewrite'
        output='css/style.css'
    %}
        <link rel="stylesheet" href="{{ asset_url }}" />
    {% endstylesheets %}


## Usage

### While developing

    grunt serve
    
We have implemented live-reload, so your changes will be reloaded in the 
browser. This will only happen in the dev-environment.


### Before launching your website

    grunt build
    
You don't have to bother if you are using deployment as we will handle it for 
you.


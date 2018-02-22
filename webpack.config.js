var webpack = require('webpack')
var path = require('path')

module.exports = {
  output: {
    filename: 'Bundle.js'
  },
  resolve: {
    modules: ['node_modules'],
    alias: {
      Framework: path.resolve(__dirname, './src/SumoCoders/FrameworkCoreBundle/Resources/assets/js/Framework/'),
      Exception: path.resolve(__dirname, './src/SumoCoders/FrameworkCoreBundle/Resources/assets/js/Exception/')
    }
  },
  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      "window.jQuery": 'jquery',
      Popper: ['popper.js', 'default']
    })
  ],
  module:  {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      }
    ]
  }
}

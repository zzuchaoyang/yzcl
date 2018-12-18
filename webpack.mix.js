const mix = require('laravel-mix')
const path = require('path')

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@supplier': path.join(__dirname, 'resources', 'assets', 'supplier'),
      '@common': path.join(__dirname, 'resources', 'assets', 'common') // 公用资源目录
    }
  }
})

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/assets/supplier/app.js', 'public/build/supplier/js')
  .version()
// if (mix.inProduction()) {
//   mix.version()
// }

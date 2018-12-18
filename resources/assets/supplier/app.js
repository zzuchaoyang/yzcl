import Vue from 'vue'
import lodash from 'lodash'
import BaiduMap from 'vue-baidu-map'
import 'normalize.css/normalize.css'// A modern alternative to CSS resets
import 'babel-polyfill'
import Element from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import Cookies from 'js-cookie'
import App from './App.vue'
import router from './router'
import store from './store'

import './icons' // icon
import './errorLog'// error log
import './permission' // permission control
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
Vue.use(Viewer)

import * as filters from './filters' // global filters

Vue.use(BaiduMap, {
  // ak 是在百度地图开发者平台申请的密钥 详见 http://lbsyun.baidu.com/apiconsole/key */
  ak: 'acVjM68TDK4lG61YpLQhGeCTzuyokf53'
})

Vue.use(Element, {
  size: Cookies.get('size') || 'mini' // set element-ui default size
})
Vue.prototype._ = lodash

// register global utility filters.
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key])
})

Vue.config.productionTip = false

new Vue({
  components: { App },
  router,
  store,
  template: '<App/>'
}).$mount('#app')

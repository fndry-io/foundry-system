// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

const fndryForm = process.env.NODE_ENV === 'development'
  ? require('../src/index.js').default
  : require('../dist/index.js').default;

Vue.config.productionTip = false;

// Using plugin
Vue.use(fndryForm);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
});

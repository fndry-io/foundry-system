// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import jQuery from 'jquery'

import App from './App'
import router from './router'

const fndryForm = process.env.NODE_ENV === 'development'
  ? require('../src/index.js').default
  : require('../dist/index.js').default;

Vue.config.productionTip = false;

// Using plugin
Vue.use(fndryForm);

jQuery.extend(true, jQuery.fn.datetimepicker.defaults, {
    icons: {
        time: 'far fa-clock',
        date: 'far fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'fas fa-calendar-check',
        clear: 'far fa-trash-alt',
        close: 'far fa-times-circle'
    }
});

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
});

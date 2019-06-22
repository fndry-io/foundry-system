// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

import FndryServices from 'fndry-services';
import FndryForm from 'fndry-form';

require('./bootstrap/axios');

import App from './App'
import router from './router'

const FndryRequest = process.env.NODE_ENV === 'development'
    ? require('../src/index').default
    : require('../dist/bundle').default;

Vue.config.productionTip = false;
Vue.config.silent = true;

// Using plugin
Vue.use(BootstrapVue);
Vue.use(FndryRequest);
Vue.use(FndryServices);
Vue.use(FndryForm);

// Import CSS
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: {App}
});


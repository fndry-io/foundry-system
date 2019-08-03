// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

import FndryServices from '../../fndry-services';
import FndryForm from '../../fndry-form/src';

require('./bootstrap/axios');

/**
 * Bootstrap the app
 */
import {axios} from './bootstrap';

Vue.use(axios);

import App from './App'
import router from './router'
import store from './store';

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters['auth/isLoggedIn']) {
            next();
            return;
        }
        next('/login');
    } else {
        next();
    }
});


const FndryRequest = process.env.NODE_ENV === 'development'
    ? require('../src/index').default
    : require('../dist/bundle').default;

Vue.config.productionTip = false;

// Using plugin
Vue.use(BootstrapVue);
Vue.use(FndryServices);
Vue.use(FndryForm);
Vue.use(FndryRequest);

// Import CSS
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: {App}
});


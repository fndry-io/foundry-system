// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import jQuery from 'jquery'
import BootstrapVue from 'bootstrap-vue'

import FndryServices from '../../fndry-services';
import FndryRequest from '../../fndry-requests/src';
import FndryForm from '../src'

import App from './App'
import router from './router'
import store from './store';

/**
 * Bootstrap the app
 */
import {axios} from './bootstrap';
Vue.use(axios);

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


// const FndryForm = process.env.NODE_ENV === 'development'
//   ? require('../src/index.js').default
//   : require('../dist/index.js').default;

Vue.config.productionTip = false;



// Using plugin
Vue.use(BootstrapVue);
Vue.use(FndryForm);
Vue.use(FndryServices);
Vue.use(FndryRequest);

// Import CSS
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

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
    store,
    template: '<App/>',
    components: { App }
});

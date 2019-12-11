import Vue from 'vue';

import {merge} from 'lodash';
import BootstrapVue from 'bootstrap-vue'

import Loader from './components/Loader';
import App from './components/App';
import Widget from './components/Widget';
import Icon from './components/Icon';
import store from './store';

import FndryServices from '../../fndry-services';
import FndryForm from '../../fndry-form/src';
import FndryRequest from '../../fndry-requests/src';

// Using plugin
Vue.use(BootstrapVue);
Vue.use(FndryServices);
Vue.use(FndryForm);
Vue.use(FndryRequest);

Vue.component('loader', Loader);
Vue.component('fndry-app', App);

export {
    Vue,
    Loader,
    Widget,
    Icon
}

const FndryVue = (config) => {
    let _config = merge({}, config, {
        store
    });
    return new Vue(_config);
};

export default FndryVue;

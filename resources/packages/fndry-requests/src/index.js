import {makeRequestForm, makeRequestConfirm} from './utils/instanceMethods';
import RequestButton from './components/RequestButton';
import RequestFormInline from './components/RequestFormInline';

/**
 * FndryRequests
 * (c) 2019
 * @license MIT
 */

const Plugin = {};

/**
 * Plugin API
 */
Plugin.install = function (Vue, options) {

    Vue.component('fndry-request-button', RequestButton);

    /**
     * @see requestForm
     */
    Vue.prototype.$fndryRequestForm = makeRequestForm(Vue);

    /**
     * @see confirmModal
     */
    Vue.prototype.$fndryRequestConfirm = makeRequestConfirm(Vue);

};

/**
 * Auto install
 */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(Plugin)
}

export {
    RequestFormInline as FndryRequestFormInline,
    RequestButton as FndryRequestButton
}

export default Plugin

import {makeRequestForm, makeRequestConfirm, makeRequestPanel} from './utils/instanceMethods';
import RequestButton from './components/RequestButton';
import RequestFormInline from './components/RequestFormInline';
import RequestForm from './components/RequestForm';

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

    /**
     * @see requestForm
     */
    Vue.prototype.$fndryRequestPanel = makeRequestPanel(Vue);

};

/**
 * Auto install
 */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(Plugin)
}

export {
    RequestFormInline as FndryRequestFormInline,
    RequestButton as FndryRequestButton,
    RequestForm as FndryRequestForm
}

export default Plugin

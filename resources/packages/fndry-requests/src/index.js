import {makeRequestForm, makeRequestConfirm, makeRequestPanel} from './utils/instanceMethods';
import RequestButton from './components/RequestButton';
import RequestFormInline from './components/RequestFormInline';
import RequestForm from './components/RequestForm';
import ConfirmButton from './components/ConfirmButton';

import {HasBrowseRequest, HasFilter, HasFormRequest} from './mixins/browse';


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
     * @return {Promise<T>}
     */
    Vue.prototype.$fndryRequestForm = makeRequestForm(Vue);

    /**
     * @see confirmModal
     * @return {Promise<T>}
     */
    Vue.prototype.$fndryRequestConfirm = makeRequestConfirm(Vue);

    /**
     * @see requestForm
     * @return {Promise<T>}
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
    RequestForm as FndryRequestForm,
    ConfirmButton as FndryConfirmButton,

    HasBrowseRequest,
    HasFilter,
    HasFormRequest
}

export default Plugin

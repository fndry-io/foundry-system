import {merge} from 'lodash';

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
     * @param {string} request The request name
     * @param {string} type The type of form to display [target|modal]
     * @param {object} options The options to pass to the sub components
     * @see modalForm
     * @see targetForm
     *
     * @returns {Promise<any>}
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

    /**
     * Convenience method for calling a fndryRequest
     *
     * @param path
     * @param params
     * @param method
     * @param data
     * @param type
     * @param options
     * @returns {response:{*}, model:{*}} the foundry response from the server, and the model data sent to the server
     */
    Vue.prototype.$fndryRequest = function(path, params, method, data, type = 'modal', options){
        let $request;
        switch(type) {
            case 'action':
                $request = this.$fndryApiService.call(
                    this.$fndryApiService.getHandleUrl(path, params),
                    method,
                    data
                );
                break;
            case 'confirm':
                $request = this.$fndryRequestConfirm(path, merge({},
                    options,
                    {params, data}
                ));
                break;
            default:
                $request = this.$fndryRequestForm(path, 'modal', merge({},
                    options,
                    {params, data}
                ));
                break;
        }

        return $request;
    }

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

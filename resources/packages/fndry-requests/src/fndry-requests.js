import {requestForm} from './utils/instanceMethods';

/**
 * FndryRequests
 * (c) 2019
 * @license MIT
 */

const FndryRequests = {};

/**
 * Plugin API
 */
FndryRequests.install = function (Vue, options) {

    //Attach fndryRequest for generating requests
    Vue.prototype.$fndryRequestForm = requestForm;

};

/**
 * Auto install
 */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(FndryRequests)
}

export default FndryRequests

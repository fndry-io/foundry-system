import Qs from 'qs';
import Axios from 'axios';

import ApiService from './src/ApiService';
import AuthStore from './src/store/auth';

export {
    AuthStore
}

const Plugin = {};

Plugin.install = function(Vue, options){

    /**
     * @type {ApiService}
     */
    Vue.prototype.$fndryApiService = new ApiService(Vue);

    /**
     * Configure axios
     */
    Vue.prototype.$http = Axios;
    Vue.prototype.$http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    Vue.prototype.$http.defaults.headers.common['Accept'] = 'application/json';

    // Format nested params correctly
    Vue.prototype.$http.interceptors.request.use(config => {

        config.paramsSerializer = params => {
            // Qs is already included in the Axios package
            return Qs.stringify(params, {
                arrayFormat: "brackets",
                encode: false
            });
        };

        return config;
    });

    Vue.prototype.$http.defaults.timeout = 10000;

};

/**
 * A request handling library
 */
export default Plugin;


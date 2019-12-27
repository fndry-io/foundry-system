import ApiService from './src/ApiService';
import AuthStore from './src/store/auth';

export {
    AuthStore
}

const install = function(Vue, options){
    /**
     * @type {ApiService}
     */
    Vue.prototype.$fndryApiService = new ApiService(Vue);
};

/**
 * A request handling library
 */
export default {
    install
}


import Qs from 'qs';
import Axios from 'axios';

const token = localStorage.getItem('token');

const axios = {
    install(Vue, options) {
        /**
         * We'll load the axios HTTP library which allows us to easily issue requests
         * to our Laravel back-end. This library automatically handles sending the
         * CSRF token as a header based on the value of the "XSRF" token cookie.
         */

        Vue.prototype.$http = Axios;

        if (token) {
            Vue.prototype.$http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        }

        Vue.prototype.$http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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
    }
};

export default axios;
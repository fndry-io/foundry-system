import ApiService, {makeService} from './src/ApiService';
import TranslateService from './src/TranslateService';

export {
    ApiService,
    TranslateService
}

const install = function(Vue, options){
    Vue.prototype.$apiService = makeService({});
};

/**
 * A request handling library
 */
export default {
    install
}


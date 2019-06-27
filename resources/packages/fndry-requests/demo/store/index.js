import Vue from 'vue';
import Vuex from 'vuex';

import {AuthStore} from '../../../../packages/fndry-services/index';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {

    },
    mutations: {

    }
});

//register modules
store.registerModule('auth', AuthStore);

export default store;
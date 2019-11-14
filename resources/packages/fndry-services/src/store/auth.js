import {merge, findIndex} from 'lodash';

const auth = {
    namespaced: true,
    state: {
        status: null,
        //token: localStorage.getItem('token') || null,
        user: {},
    },
    mutations: {
        auth_success(state, {user, token}){
            state.status = 'success';
            //state.token = token;
            state.user = user;
        },
        auth_sending(state){
            state.status = 'loading';
        },
        auth_error(state){
            state.status = 'error';
        },
        auth_reset(state){
            state.status = null;
            //state.token = null;
            state.user = null;
        },
        auth_settings(state, settings){
            state.user.settings = settings;
        },
        update_user(state, {user}){
            state.user = merge({}, user);
        }
    },
    getters : {
        isLoggedIn: state => !!state.user,
        authStatus: state => state.status,
        userSettings: (state) => {
            if (state.user.settings) {
                return state.user.settings;
            } else {
                return {};
            }
        },
        isAdmin (state) {
            return state.user.is_admin || state.user.is_super_admin;
        },
        abilities: (state) => {
            if (state.user && state.user.abilities) {
                return state.user.abilities;
            } else {
                return [];
            }
        }
    },
    actions: {
        login({commit}, data){
            return this._vm.$fndryApiService.handle('/api/auth/login', {}, data).then((response) => {
                commit('auth_success', response.data);
                // localStorage.setItem('token', response.data.token);
                // this._vm.$http.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
                return response;
            });
        },
        user({commit}, data){
            return this._vm.$fndryApiService.call('/api/auth/user', 'GET', {}).then((response) => {
                commit('auth_success', response.data);
                // localStorage.setItem('token', response.data.token);
                // this._vm.$http.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
                return response;
            });
        },
        reset({commit}){
            commit('auth_reset');
        },
        logout({commit}){
            return this._vm.$fndryApiService.handle('/api/auth/logout', {}, {guard: 'system'}).then((response) => {
                commit('auth_reset');
                // localStorage.removeItem('token');
                // delete this._vm.$http.defaults.headers.common['Authorization'];
                return response;
            });
        },
        forgotPassword({commit}, data){
            return this._vm.$fndryApiService.handle('/api/auth/forgot', {}, data);
        },
        resetPassword({commit}, data){
            return this._vm.$fndryApiService.handle('/api/auth/reset', {}, data);
        },
        syncUserSettings({commit, state}, settings){
            let _settings = merge({}, state.user.settings, settings);
            commit('auth_settings', _settings);
            return this._vm.$fndryApiService.call('/api/auth/settings', 'POST', {settings: _settings});
        },
        hasAbility({state, getters}, ability){
            return new Promise((resolve, reject) => {
                if (getters.isLoggedIn(state) && (getters.isAdmin(state) || (state.user.abilities && findIndex(state.user.abilities, ability) !== -1))) {
                    resolve();
                } else {
                    reject();
                }
            })
        }
    }
};


export default auth;

import {merge} from 'lodash';

const auth = {
    namespaced: true,
    state: {
        status: '',
        token: localStorage.getItem('token') || '',
        user: {},
    },
    mutations: {
        auth_success(state, {user, token}){
            state.status = 'success';
            state.token = token;
            state.user = user;
        },
        auth_sending(state){
            state.status = 'loading';
        },
        auth_error(state){
            state.status = 'error';
        },
        auth_reset(state){
            state.status = '';
            state.token = '';
            state.user = '';
        },
        auth_settings(state, settings){
            state.user.settings = settings;
        },
    },
    getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        userSettings: state => state.user.settings,
    },
    actions: {
        login({commit}, data){
            return this._vm.$fndryApiService.handle('/api/auth/login', {}, data).then((response) => {
                commit('auth_success', response.data);
                localStorage.setItem('token', response.data.token);
                this._vm.$http.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
                return response;
            });
        },
        user({commit}, data){
            return this._vm.$fndryApiService.call('/api/auth/user', 'GET', {}).then((response) => {
                commit('auth_success', response.data);
                localStorage.setItem('token', response.data.token);
                this._vm.$http.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
                return response;
            });
        },
        reset({commit}){
            commit('auth_reset');
        },
        logout({commit}){
            return this._vm.$fndryApiService.handle('/api/auth/logout', {}, {guard: 'api'}).then((response) => {
                commit('auth_reset');
                localStorage.removeItem('token');
                delete this._vm.$http.defaults.headers.common['Authorization'];
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
            let _settings = merge({}, state.settings, settings);
            commit('auth_settings', settings);
            return this._vm.$fndryApiService.call('/api/auth/settings', 'POST', {settings: _settings});
        },
    }
};


export default auth;
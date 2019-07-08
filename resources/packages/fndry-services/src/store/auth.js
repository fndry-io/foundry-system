

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
    },
    getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
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
        }
    }
};


export default auth;
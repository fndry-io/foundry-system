

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
            return new Promise((resolve, reject) => {
                this._vm.$fndryApiService.handle('foundry.system.auth.login', {}, data).then((response) => {
                    commit('auth_success', response.data);
                    localStorage.setItem('token', response.data.token);
                    this._vm.$http.defaults.headers.common['Authorization'] = response.data.token;
                    resolve(response);
                });
            })
        },
        logout({commit}){
            return new Promise((resolve, reject) => {
                this._vm.$fndryApiService.handle('foundry.system.auth.logout', {}, {guard: 'api'}).then((response) => {



                    commit('auth_reset');
                    localStorage.removeItem('token');
                    delete this._vm.$http.defaults.headers.common['Authorization'];
                    resolve(response);
                });
            })
        }
    }
};


export default auth;
<template>
    <div id="fndry-app" ref="fndry-app">
        <div v-if="loaded">
            <slot></slot>
        </div>
        <div v-else>
            <div class="app-loading">
                <div>
                    <b-spinner variant="primary"></b-spinner>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {isEmpty} from 'lodash';

    const appMixin = {
        data(){
            return {
                loaded: false
            };
        },
        beforeCreate: function () {

            /**
             * The following code is for the purpose of checking the user request to the server
             *
             * If the response was a 401, unauthenticated, then we need to ensure the user is get's redirected to the
             * login screen.
             *
             * The initial check is ensuring that after a page refresh or new url entered in the browse, that we check
             * the user is still logged in on the server.
             *
             * The second check is an interceptor to ensure that if we get a 401 on any call, the user is redirected
             * to the log ing page.
             *
             * See main.js of the router.beforeEach callback for other redirects related to 401.
             *
             */

            //if we don't have the user object, get it
            if (isEmpty(this.$store.state.auth.user)) {
                this.$store.dispatch('auth/user').then((response) => {}, (response) => {
                    if (response.code === 401) {
                        this.$store.dispatch('auth/reset');
                    }
                }).finally(() => {
                    this.loaded = true;
                });
            } else {
                this.loaded = true;
            }

            //ensure 401's are handled
            this.$http.interceptors.response.use((response) => {
                if (response.status === 401 || response.data.code === 401) {
                    this.$store.dispatch('auth/reset');
                    if (this.loaded) {
                        this.$toasted.show(`You need to log in`);
                        this.$router.push('/auth/login');
                    }
                }
                return response;
            });

            //ensure 403's are handled
            this.$http.interceptors.response.use((response) => {
                if (response.status === 403 || response.data.code === 403) {
                    //this.$toasted.show(`Permission denied`);
                }
                return response;
            });

            this.$http.interceptors.response.use((response) => {
                if (response.data && response.data.message) {
                    if (this.$toasted) {
                        this.$toasted.show(response.data.message, {
                            icon: 'check'
                        });
                    }
                }
                if (response.data && response.data.code !== 401 && response.data.code !== 403) {
                    if (this.$toasted) {
                        this.$toasted.show(response.data.error, {
                            icon: 'exclamation-circle'
                        });
                    }
                }
                return response;
            })
        }
    };

    export {
        appMixin
    };

    export default {
        name: 'fndry-app',
        mixins: [
            appMixin
        ],
    }
</script>

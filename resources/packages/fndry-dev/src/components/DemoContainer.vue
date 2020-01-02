<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand href="#">{{title}}</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <slot name="links">

                </slot>
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown v-if="!isLoggedIn" right>
                        <template v-slot:button-content>
                            Log In As <b-spinner v-if="loading" small></b-spinner>
                        </template>
                        <b-dropdown-item v-for="user in users" @click="() => logInAs(user)" :key="user.username">{{user.display_name}}</b-dropdown-item>
                    </b-nav-item-dropdown>
                    <login-modal v-if="!isLoggedIn"></login-modal>
                    <b-nav-item-dropdown v-if="isLoggedIn" right>
                        <template v-slot:button-content>
                            {{$store.state.auth.user.display_name}}
                        </template>
                        <b-dropdown-item @click="logout">Log Out <b-spinner v-if="loading" small></b-spinner></b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <slot v-bind:isLoggedIn="isLoggedIn">
        </slot>
    </div>
</template>

<script>

    import {merge} from 'lodash';

    import LoginModal from "./LoginModal";

    export default {
        name: "Demo",
        props: {
            title: String
        },
        data() {
            return {
                loading: false,
                users: [
                    {
                        display_name: 'Super Admin',
                        email: 'superadmin@domain.com',
                        password: 'test1234'
                    },
                    {
                        display_name: 'Admin',
                        email: 'admin@domain.com',
                        password: 'test1234'
                    },
                    {
                        display_name: 'Manager',
                        email: 'manager@domain.com',
                        password: 'test1234'
                    },
                    {
                        display_name: 'Dummy 1',
                        email: 'dummy1@domain.com',
                        password: 'test1234'
                    }
                ]
            };
        },
        components: {
            LoginModal
        },
        methods: {
            logout(){
                this.loading = true;
                this.$store.dispatch('auth/logout').finally(() => {
                    this.loading = false;
                });
            },
            logInAs(user){
                this.loading = true;

                let payload = merge({}, user, {
                    guard: 'system',
                });

                this.$store.dispatch('auth/login', payload)
                    .finally(() => {
                        this.loading = false;
                    })
                ;
            }
        },
        computed: {
            isLoggedIn: function() {
                return this.$store.getters['auth/isLoggedIn'];
            }
        }
    }
</script>

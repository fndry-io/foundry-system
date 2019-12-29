<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand href="#">{{title}}</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <slot name="links">

                </slot>
                <b-navbar-nav class="ml-auto">
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
    import LoginModal from "./LoginModal";

    export default {
        name: "Demo",
        props: {
            title: String
        },
        data() {
            return {
                loading: false
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
            }
        },
        computed: {
            isLoggedIn: function() {
                return this.$store.getters['auth/isLoggedIn'];
            }
        }
    }
</script>

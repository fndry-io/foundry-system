<template>
    <div>
        <b-button @click="initModal" :disabled="loading" v-if="!isLoggedIn">Log In <b-spinner v-if="loading" small></b-spinner></b-button>
        <ValidationObserver ref="observer" v-slot="{ invalid }">
            <b-modal ref="login-modal" id="login-modal">
                <template v-slot:modal-title>
                    Log In
                </template>
                <div class="d-block text-center">
                    <loader :loading="loading">
                        <b-form @submit.prevent="submit">
                            <ValidationProvider rules="required|email" v-slot="{ errors, valid }" name="Email">
                                <b-form-group
                                    id="input-group-email"
                                    :invalid-feedback="errors[0]"
                                    :state="valid"
                                >
                                    <b-form-input
                                        id="input-1"
                                        v-model="email"
                                        type="email"
                                        required
                                        placeholder="Your email address"
                                    ></b-form-input>
                                </b-form-group>
                            </ValidationProvider>
                            <ValidationProvider rules="required|min:8" v-slot="{ errors, valid }" name="Password">
                                <b-form-group
                                    id="input-group-2"
                                    :invalid-feedback="errors[0]"
                                    :state="valid"
                                >
                                    <b-form-input
                                        type="password"
                                        id="input-password"
                                        v-model="password"
                                        required
                                        placeholder="Password"
                                    ></b-form-input>
                                </b-form-group>
                            </ValidationProvider>
                        </b-form>
                    </loader>
                </div>
                <template v-slot:modal-footer>
                    <b-button type="submit" variant="primary" :disabled="loading || invalid" @click="submit">Log In</b-button>
                </template>
            </b-modal>
        </ValidationObserver>
    </div>
</template>

<script>

    import {ValidationProvider, ValidationObserver} from 'vee-validate';

    export default {
        name: 'login-modal',
        components: {
            ValidationProvider,
            ValidationObserver
        },
        data() {
            return {
                loading: false,
                email: '',
                password: ''
            }
        },
        methods: {
            initModal() {
                this.loading = true;
                this.$http.get('http://localhost/', {
                        withCredentials: true
                    })
                    .then((response) => {
                        this.showModal();
                    })
                    .finally(() => {
                        this.loading = false;
                    })
                ;
            },
            showModal() {
                this.$refs['login-modal'].show();
            },
            hideModal() {
                this.$refs['login-modal'].hide();
            },
            async submit() {

                const isValid = await this.$refs.observer.validate();
                if (!isValid) {
                    return;
                }

                this.loading = true;

                let payload = {
                    email: this.email,
                    password: this.password,
                    guard: 'system',
                };

                this.$store.dispatch('auth/login', payload)
                    .then((response) => {
                        //this.hideModal();
                    })
                    .finally(() => {
                        this.loading = false;
                    })
                ;
            }
        },
        computed: {
            isLoggedIn: function(){
                return this.$store.getters['auth/isLoggedIn'];
            }
        }
    }
</script>

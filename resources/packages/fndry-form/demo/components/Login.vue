<template>
    <div class="demo">
        <h2>Login</h2>
        <div class="container text-left">
            <b-row class="justify-content-center">
                <b-col md="6">
                    <b-card-group>
                        <b-card no-body class="d-md-down-none">
                            <b-card-body class="text-left">

                                <h2 class="pb-4">Log In</h2>
                                <ValidationObserver ref="observer" v-slot="{ invalid }">

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

                                        <b-row>
                                            <b-col class="text-right">
                                                <!--<b-button type="submit" variant="link" :disabled="submitting || invalid"><span class="">Forgot Password?</span></b-button>-->
                                                <b-button type="submit" variant="primary" :disabled="submitting || invalid">Log In<b-spinner type="grow" small class="ml-4" v-if="submitting"></b-spinner></b-button>
                                            </b-col>
                                        </b-row>

                                    </b-form>
                                </ValidationObserver>

                            </b-card-body>
                        </b-card>
                    </b-card-group>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>

    import { ValidationProvider, ValidationObserver } from 'vee-validate';

    export default {
        name: 'Login',
        components: {
            ValidationProvider,
            ValidationObserver
        },
        data() {
            return {
                submitting: false,
                email: '',
                password: ''
            }
        },
        methods: {
            async submit(){

                const isValid = await this.$refs.observer.validate();
                if (!isValid) {
                    return;
                }

                this.submitting = true;

                let payload = {
                    email: this.email,
                    password: this.password,
                    guard: 'api',
                };

                this.$store.dispatch('auth/login', payload)
                    .then((response) => {
                        this.$bvToast.toast(`Welcome back ${response.data.first_name}`, {
                            autoHideDelay: 5000,
                            append: true
                        });
                        this.$router.push('/');
                    }, (response) => {
                        this.submitting = false;
                        console.log(response);
                    })
                ;
            }
        }
    }
</script>

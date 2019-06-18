<template>
    <div class="demo">
        <h2>Request/Response Tester</h2>
        <div class="container text-left">
            <div class="m-4">
                <div v-if="loading" class="text-center">
                    <b-spinner label="Loading..."></b-spinner>
                </div>

                <div v-if="!loading">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Request</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <b-form-group label="Type of Form">
                                                    <b-form-radio v-model="type" name="type" value="inline">Inline</b-form-radio>
                                                    <b-form-radio v-model="type" name="type" value="target">Target</b-form-radio>
                                                    <b-form-radio v-model="type" name="type" value="modal">Modal</b-form-radio>
                                                </b-form-group>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div v-if="type === 'target'" class="form-group">
                                                <b-form-group label="Target">
                                                    <b-form-radio v-model="options.target" name="target" value="inlineFormTarget" inline>inlineFormTarget</b-form-radio>
                                                </b-form-group>
                                            </div>
                                            <div v-if="type === 'target'" class="form-group">
                                                <b-form-checkbox v-model="options.inline" name="check-button" switch>
                                                    Inline Fields
                                                </b-form-checkbox>
                                            </div>
                                            <div v-if="type === 'modal'" class="form-group">
                                                <b-form-group label="Size">
                                                    <b-form-radio v-model="options.size" name="size" value="sm">Small</b-form-radio>
                                                    <b-form-radio v-model="options.size" name="size" value="lg">Large</b-form-radio>
                                                    <b-form-radio v-model="options.size" name="size" value="xl">Extra Large</b-form-radio>
                                                </b-form-group>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="request">Select a Form</label>
                                        <select id="request" class="form-control" @change="onListChange" v-model="request">
                                            <option v-for="(name, index) in requests" :key="index">{{name}}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <button @click="handleLoadRequest" class="btn btn-primary">Load Request</button>
                                    </div>
                                    <div v-if="request && type === 'target'">
                                        <hr>
                                        <div id="inlineFormTarget"></div>
                                    </div>
                                    <div v-if="request && type === 'inline'">
                                        <hr>
                                        <fndry-request-form-inline :request="request" @success="onResponse" @fail="onResponse" @submitting="onSubmitting" :key="request"></fndry-request-form-inline>
                                    </div>
                                </div>
                            </div>
                            &nbsp;
                            <div class="card" v-if="request && model">
                                <div class="card-body" >
                                    <h5 class="card-title">Model Output</h5>
                                    <hr>
                                    <div>
                                        <vue-json-pretty :data="model"></vue-json-pretty>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Response</h5>
                                    <hr>
                                    <div v-if="response">
                                        <vue-json-pretty :data="response"></vue-json-pretty>
                                    </div>
                                    <div v-else>
                                        No response yet...
                                    </div>
                                    <div v-if="submitting" class="text-center">
                                        <b-spinner label="Loading..."></b-spinner>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

    import VueJsonPretty from 'vue-json-pretty';
    import FndryRequestFormInline from '../../src/components/RequestFormInline';
    import {merge} from 'lodash';

    /**
     * Request Form Tester
     *
     * This component is designed to help test registered requests in the system
     *
     */
    export default {
        name: 'RequestFormTester',
        components: {
            VueJsonPretty,
            FndryRequestFormInline
        },
        data: function(){
            return {
                loading: true,
                submitting: false,
                requests: null,
                type: 'modal',
                url: null,
                request: null,
                response: null,
                route: '/system/request/view',
                state: {},
                schema: {},
                model: {},
                options: {
                    target: 'inlineFormTarget',
                    size: 'lg',
                    inline: false,
                    data: {
                        email: 'admin@domain.com'
                    }
                }
            }
        },
        mounted() {
            this.getList();
        },
        methods: {

            /**
             * Handle the list change
             *
             * This will trigger the form-view component to re-render
             */
            onListChange() {
                this.response = null;
            },

            /**
             * Get the list of possible requests the system can receive
             */
            getList(){
                this.loading = true;
                this.$apiService
                    .call('/system/request/all', 'GET', {})
                    .then((response) => {
                        this.requests = response.data;
                        this.loading = false;
                    })
                ;
            },

            /**
             * On Success handler for updating the response from the server
             */
            onResponse(response, model) {
                this.response = response;
                this.model = model;
                this.submitting = false;
            },

            /**
             * On Submitting form
             */
            onSubmitting(model) {
                this.submitting = true;
                this.model = model;
                this.response = null;
            },

            handleLoadRequest() {
                this.$fndryRequestForm(this.request, this.type, this.options).then(({response, model}) => {
                    this.onResponse(response, model);
                }).catch(({type, response, model}) => {
                    this.onResponse(response, model);
                });
            }
        }
    }

</script>
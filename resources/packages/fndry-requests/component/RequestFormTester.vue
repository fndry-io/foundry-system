<template>
    <div class="m-4">

        <h2>Request/Response Tester</h2>
        <hr>

        <div v-if="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div v-if="!loading">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Request</h5>
                            <hr>
                            <div class="form-group">
                                <label for="request">Select a Form</label>
                                <select id="request" class="form-control" @change="onListChange" v-model="request">
                                    <option v-for="(name, index) in requests" :key="index">{{name}}</option>
                                </select>
                            </div>
                            <hr>
                            <div v-if="request">
                                <!--<form-view :request="request" @success="onResponse" @fail="onResponse" @submitting="onSubmitting" :key="request"></form-view>-->
                                <form-request :request="request" @success="onResponse" @fail="onResponse" @submitting="onSubmitting" :key="request"></form-request>

                                <div v-if="model">
                                    <vue-json-pretty :data="model"></vue-json-pretty>
                                </div>

                            </div>
                            <div v-else>
                                No form selected yet...
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>

    import ApiService, {route} from 'fndry-services/services/ApiService';
    import VueJsonPretty from 'vue-json-pretty';
    import {FormRequest} from 'fndry-form/src';

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
            FormRequest
        },
        data: function(){
           return {
               loading: true,
               requests: null,
               url: null,
               request: null,
               response: null,
               route: '/system/request/view',
               state: {},
               schema: {},
               model: {}
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
                ApiService
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
            onResponse(response) {
                this.response = response;
            },

            /**
             * On Submitting form
             */
            onSubmitting() {
                this.response = null;
            }
        }
    }

</script>
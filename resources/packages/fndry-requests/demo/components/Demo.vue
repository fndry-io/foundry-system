<template>
    <div class="demo">
        <h2>Request/Response Tester</h2>
        <div class="text-left">
            <div class="m-4">
                <div v-if="loading" class="text-center">
                    <b-spinner label="Loading..."></b-spinner>
                </div>

                <div v-if="!loading">
                    <div class="row">
                        <div class="col-md-4">
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
                                        <div class="input-group mb-3">
                                            <select id="request" class="form-control" @change="onListChange" v-model="request">
                                                <option v-for="(name, uri) in requests" :key="name" :value="uri">{{name}}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <b-button variant="outline-primary" @click="getList">Reload</b-button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 mb-5">
                                        <div class="form-group">
                                            <label>Parameters</label>
                                            <div v-if="params">
                                                <div v-for="(param, index) in params" :key="index">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input class="form-control" :name="`key${index}`" :value="params[index].key" @input="(e) => updateParamKey(e, index)" placeholder="name...">
                                                            <input class="form-control" :name="`value${index}`" :value="params[index].value" @input="(e) => updateParamValue(e, index)" placeholder="value...">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary" type="button" @click="() => removeParam(index)">X</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else>
                                                None set
                                            </div>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-outline-primary" type="button" @click="addParam">Add Blank</button>
                                            <button class="btn btn-outline-secondary" type="button" @click="addEntity">Add Entity</button>
                                            <button class="btn btn-outline-danger" type="button" @click="clearParams">Reset</button>
                                        </div>

                                    </div>

                                    <div>
                                        <button @click="handleLoadRequest" class="btn btn-primary">Load Request</button> &nbsp; <button @click="handleSubmitRequest" class="btn btn-primary">Submit Request</button>
                                    </div>
                                </div>
                            </div>

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
                        <div class="col-md-4">

                            <div v-if="request && type === 'target'">
                                <div class="card">
                                    <div class="card-body" >
                                        <div id="inlineFormTarget"></div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="request && type === 'inline' && inline">
                                <div class="card">
                                    <div class="card-body" >
                                        <fndry-request-form-inline :params="options.params" :title="true" :request="request" @success="onResponse" @fail="onResponse" @submitting="onSubmitting" :key="key"></fndry-request-form-inline>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-4">
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
    import {merge, uniqueId, forEach} from 'lodash';

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
                type: 'inline',
                url: null,
                request: null,
                response: null,
                state: {},
                schema: {},
                model: {},
                inline: false,
                key: 0,

                defaults: {
                    edit: {
                        entity: {
                            key: '_entity',
                            value: ''
                        }
                    }
                },
                params: {

                },

                options: {
                    target: 'inlineFormTarget',
                    size: 'lg',
                    inline: false,
                    data: {
                        email: 'admin@domain.com',
                        guard: 'api'
                    },
                    params: {}
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
                this.inline = false;
            },

            /**
             * Get the list of possible requests the system can receive
             */
            getList(){
                this.loading = true;
                this.$fndryApiService
                    .call('/api/system/request/all', 'GET', {})
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
                this.key++;
                this.options.params = this.getParams();
                this.options = merge({}, this.options);

                if (this.type === 'inline') {
                    this.inline = true;
                } else {
                    this.inline = false;
                    this.$fndryRequestForm(this.request, this.type, this.options).then(({response, model}) => {
                        this.onResponse(response, model);
                    }).catch(({type, response, model}) => {
                        this.onResponse(response, model);
                    });
                }

            },
            handleSubmitRequest() {
                this.onSubmitting({});
                let params = this.getParams();
                this.$fndryApiService.call(this.$fndryApiService.getViewUrl(this.request, params), 'POST', {})
                    .then((res) => {
                        this.onResponse(res, {});
                    }).catch((res) => {
                        this.onResponse(res, {});
                    })
                ;
            },
            updateParamKey(e, index){
                if (this.params[index]) {
                    this.params[index].key = e.target.value;
                }
            },
            updateParamValue(e, index){
                if (this.params[index]) {
                    this.params[index].value = e.target.value;
                }
            },
            addParam(){
                let id = uniqueId();
                this.params[id] = {
                    key: '',
                    value: ''
                };
                this.params = merge({}, this.params);
            },
            removeParam(index){
                delete(this.params[index]);
                this.params = merge({}, this.params);
            },
            clearParams(){
                this.params = {};
            },
            getParams() {
                let params = {};
                forEach(this.params, function(item){
                    params[item.key] = item.value;
                });
                return params
            },
            addEntity(){
                this.params = merge({}, this.params, {
                    entity: {
                        key: '_entity',
                        value: '',
                    }
                });
            }
        }
    }

</script>
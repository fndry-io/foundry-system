<template>
    <div class="demo">
        <h2>Request/Response Tester</h2>
        <div class="text-left">
            <div class="m-4">
                <div v-if="loading" class="text-center">
                    <b-spinner label="Loading..."></b-spinner>
                </div>

                <div v-if="!loading">
                    <div class="d-flex">
                        <div class="flex-grow-0" style="max-width:700px; min-width: 500px;">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <select id="request" class="form-control" @change="onListChange" v-model="request">
                                                <option v-for="(name, uri) in requests" :key="name" :value="uri">{{name}}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <b-button variant="outline-primary" @click="getList">Reload</b-button>
                                                <b-dropdown id="dropdown-params" text="Params">
                                                    <b-dropdown-item @click="() => addParam()">Add Blank</b-dropdown-item>
                                                    <b-dropdown-item @click="addEntity">Add Entity</b-dropdown-item>
                                                    <b-dropdown-item @click="addReference">Add Reference</b-dropdown-item>
                                                    <b-dropdown-item @click="clearParams">Reset</b-dropdown-item>
                                                </b-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="params" class="mb-3">
                                        <div v-for="(param, index) in params" :key="index">
                                            <div class="form-group mb-0">
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
                                    <div class="row">
                                        <div class="col">
                                            <b-form-group label="">
                                                <b-form-radio v-model="type" name="type" value="inline" inline>Inline</b-form-radio>
                                                <b-form-radio v-model="type" name="type" value="target" inline>Target</b-form-radio>
                                                <b-form-radio v-model="type" name="type" value="modal" inline>Modal</b-form-radio>
                                            </b-form-group>
                                            <div v-if="type === 'target'">
                                                <b-form-group>
                                                    <b-form-radio v-model="options.target" name="target" value="inlineFormTarget" inline>inlineFormTarget</b-form-radio>
                                                    <b-form-checkbox v-if="type === 'target'" v-model="options.inline" name="check-button" switch inline>
                                                        Inline Fields
                                                    </b-form-checkbox>
                                                </b-form-group>
                                            </div>
                                            <div v-if="type === 'modal'">
                                                <b-form-group>
                                                    <b-form-radio v-model="options.size" name="size" value="sm" inline>Small</b-form-radio>
                                                    <b-form-radio v-model="options.size" name="size" value="lg" inline>Large</b-form-radio>
                                                    <b-form-radio v-model="options.size" name="size" value="xl" inline>Extra Large</b-form-radio>
                                                </b-form-group>
                                            </div>
                                        </div>
                                        <div class="col buttons text-right">
                                            <button @click="handleLoadRequest" class="btn btn-primary">View</button>&nbsp;
                                            <button @click="handleSubmitRequest" class="btn btn-primary">Handle</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="request && type === 'target'">
                                <div class="card mb-2">
                                    <div class="card-body" >
                                        <div id="inlineFormTarget"></div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="request && type === 'inline' && inline">
                                <div class="card mb-2">
                                    <div class="card-body" >
                                        <fndry-request-form-inline :params="options.params" :title="true" :request="request" @success="onResponse" @fail="onResponse" @submitting="onSubmitting" :key="key"></fndry-request-form-inline>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="flex-grow-1 ml-4">
                            <div class="mb-4" v-if="request && model">
                                <h5 class="card-title">Model Output</h5>
                                <hr>
                                <div>
                                    <vue-json-pretty :data="model"></vue-json-pretty>
                                </div>
                            </div>
                            <div class="mb-2">
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
</template>

<script>

    import VueJsonPretty from 'vue-json-pretty';
    import FndryRequestFormInline from '../../src/components/RequestFormInline';
    import {merge, uniqueId, forEach, findKey, set} from 'lodash';

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
            if (this.$route.query) {
                forEach(this.$route.query, (value, key) => {
                    if (key !== 'request') {
                        this.addParam(key, value);
                    }
                })
            }
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
                        if (this.$route.query.request) {
                            let uri;
                            if (uri = findKey(this.requests, (name) => name === this.$route.query.request)) {
                                this.request = uri;
                            }
                        }
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
            addParam(key = null, value = ''){
                let id = (key) ? key : uniqueId();
                this.params[id] = {
                    key: key,
                    value: value
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
                    set(params, item.key, item.value);
                });
                return params
            },
            addEntity(){
                this.addParam('_entity', '');
            },
            addReference(){
                this.addParam('reference_type', '');
                this.addParam('reference_id', '');
            }
        }
    }

</script>
import { ValidationObserver } from 'vee-validate';
import FndryForm from '../../../fndry-form/src';
import {each, merge} from 'lodash';

export default {
    components: {
        ValidationObserver
    },
    mixins: [
        FndryForm
    ],
    props: {
        request: String,
        data: {
            type: Object,
            default: function () {
                return {};
            }
        },
        params: {
            type: Object,
            default: function(){
                return {};
            }
        }
    },
    data () {
        return {
            loading: true,
            submitting: false,
            schema: null,
            state: {},
            model: {},
            errors: null,
            response: {},
            dirty: false,
            activeButton: null
        }
    },
    created(){
        if(this.request) {
            this.getSchema();
        }
    },
    methods: {
        getSchema: function () {
            this.$fndryApiService.call(this.$fndryApiService.getViewUrl(this.request, this.params), 'GET', {_form: true})
                .then((res) => {
                    this.setSchema(res.data);
                }, (res) => {
                    this.loading = false;
                    this.onCancel();
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        setSchema: function(data) {
            this.schema = data;
            if (data.values) {
                this.model = merge(this.model, data.values);
            }
            if (this.data) {
                this.model = merge(this.model, this.data);
            }
        },
        onModelUpdated(model){
            this.dirty = true;
            this.model = model;
            this.$emit('update', this.model);
        },
        submit(action, method){
            this.$refs.observer.validate().then((isValid) => {
                if (isValid) {
                    this.onSubmitting();
                    this.submitting = true;
                    this.response = {};
                    this.errors = null;

                    if (action === undefined) {
                        action = this.$fndryApiService.getHandleUrl(this.request, merge({}, this.schema.params, this.params));
                    }

                    if (method === undefined) {
                        method = 'POST';
                    }

                    let that = this;

                    this.$fndryApiService.call(action, method, this.model)
                        .then(function(response) {
                            that.response = response;
                            that.onSuccess(response);
                            //console.log('call success');
                        }, (response) => {
                            that.response = response;
                            that.submitting = false;
                            that.onFail(response);
                            //console.log('call fail');
                        })
                        .finally(() => {
                            that.submitting = false;
                            that.activeButton = null;
                        })
                    ;
                } else {
                    this.activeButton = null;
                }
            });
        },
        onSubmitting: function(){
            this.$emit('submitting', this.model);
        },
        onSuccess: function(response){
            this.$emit('success', response, this.model);
        },
        onFail: function(response){
            if (response.code === 422) {
                this.errors = response.data;
            }
            this.$emit('fail', response, this.model);
        },
        onCancel: function(){
            this.$emit('cancel');
        },
        onSubmit(){
            //find the submit button, else call submit
            if (this.schema.buttons) {
                each(this.schema.buttons, (button) => {
                    if (button.type === 'submit') {
                        this.handleButtonClick(button);
                    }
                })
            } else {
                this.submit();
            }
        },
        handleButtonClick(button, key){
            this.activeButton = key;
            switch (button.type) {
                case 'submit':
                    let action = this.schema.action || undefined;

                    if (button.hasOwnProperty('action') && button.action) {
                        action = button.action;
                    }

                    if (button.hasOwnProperty('params') && button.params) {
                        action = this.$fndryApiService.getHandleUrl(action, button.params);
                    }

                    if (action && this.schema.params) {
                        action = this.$fndryApiService.getHandleUrl(action, merge({}, this.schema.params, this.params));
                    }

                    let method = this.schema.method || 'POST';
                    if (button.hasOwnProperty('method')) {
                        method = button.method;
                    }

                    this.submit(action, method);
                    break;
                case 'cancel':
                    this.onCancel();
                    break;
            }
        },
    }
};

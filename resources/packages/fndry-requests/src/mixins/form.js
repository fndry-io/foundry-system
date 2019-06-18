import { ValidationObserver } from 'vee-validate';
import FndryForm from 'fndry-form';
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
            response: {}
        }
    },
    mounted(){
        if(this.request) {
            this.getSchema();
        }
    },
    methods: {
        getSchema: function () {
            this.$apiService.call(this.$apiService.getViewUrl(this.request, this.params), 'GET', {})
                .then((res) => {
                    this.setSchema(res.data);
                }, (res) => {})
                .finally(() => {
                    this.loading = false;
                });
        },
        setSchema: function(data) {
            this.schema = data;
        },
        onModelUpdated(model){
            this.model = model;
        },
        async submit(action, method){
            const isValid = await this.$refs.observer.validate();
            if (isValid) {
                this.onSubmitting();
                this.submitting = true;
                this.response = {};
                this.errors = null;

                if (action === undefined) {
                    action = this.$apiService.getHandleUrl(this.request, this.params);
                }
                if (method === undefined) {
                    method = 'POST';
                }

                this.$apiService.call(action, method, this.model)
                    .then((response) => {
                        this.response = response;
                        this.onSuccess(response);
                    }, (response) => {
                        this.response = response;
                        this.onFail(response);
                    })
                    .finally(() => {
                        this.submitting = false;
                    })
                ;
            }
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
        handleButtonClick(button){
            switch (button.type) {
                case 'submit':
                    let action = this.schema.action || '';
                    if (button.hasOwnProperty('action')) {
                        action = button.action;
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
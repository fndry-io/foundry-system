<template>
    <div>
        <div v-if="!schema" class="text-center">
            <div class="spinner-border text-primary text-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div v-else="schema">
            <form-state :state="state" @submit.prevent="onSubmit">
                <form-schema :schema="schema" :model="model" @model-update="(nModel) => this.model = nModel"></form-schema>
                <button type="submit">Submit</button>
            </form-state>
        </div>
    </div>
</template>

<script>

    import ApiService, {route, getViewRequestUri} from 'fndry-services/services/ApiService';
    import FormState from './FormState';
    import FormSchema from './FormSchema';

    export default {
        name: "FormRequest",
        components: {
            FormSchema,
            FormState
        },
        props: {
            request: String,
            params: {
                type: Object,
                default: function(){
                    return {};
                }
            }
        },
        data () {
            return {
                schema: null,
                //This helps up to force the component to re-render if the url prop changes
                componentKey: 0,
                state: {},
                model: {}
            }
        },
        mounted(){
            if(this.request) {
                this.getSchema();
            }
        },
        watch: {
            request: function(newVal, oldVal){
                if (newVal !== oldVal) {
                    this.reset();
                }
            }
        },
        methods: {
            handleRequest() {

            },
            getSchema: function () {
                ApiService.call(route(getViewRequestUri(this.request), this.params), 'GET', {})
                    .then((res) => {
                        this.setSchema(res.data);
                    }, (res) => {});

            },
            setSchema: function(data) {
                this.schema = data;
            },
            onSubmit: function(){
                if (this.formstate.$invalid) {

                }

            },
            onSubmitting: function(model){
                this.$emit('submitting', model);
            },
            onSuccess: function(response, model){
                this.$emit('success', response, model);
            },
            onFail: function(response, model){
                this.$emit('fail', response, model);
            },
            onCancel: function(){
                this.$emit('cancel');
            },
            reset: function(){
                this.componentKey += 1;
                this.model = {};
                this.schema = {};
            }
        }
    }
</script>


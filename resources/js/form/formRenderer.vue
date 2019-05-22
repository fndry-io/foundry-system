<template>
    <div>
        <div v-if="!data" class="text-center">
            <div class="spinner-border text-primary text-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <form-generator
                v-if="data"
                :schema="data"
                :model="modelData"
                :options="formOptions"
                :multiple="multiple"
                :is-new-model="isNewModel"
                @success="onSuccess"
                @cancel="onCancel"
                :inline="inline"
                :tag="tag"></form-generator>
    </div>
</template>

<script>

    import FormGenerator from './formGenerator';
    import ApiService from '../services/ApiService';

    export default {
        name: "formRenderer",
        components: { 'form-generator': FormGenerator },
        props: {
            url : String,
            schema: Object,
            model: Object,
            options: {
                type: Object,
                default() {
                    return {
                        validateAfterLoad: false,
                        validateAsync: false,
                        validateAfterChanged: false,
                        validationErrorClass: "error",
                        validationSuccessClass: ""
                    };
                }
            },
            multiple: {
                type: Boolean,
                default: false
            },
            isNewModel: {
                type: Boolean,
                default: false
            },
            tag: {
                type: String,
                default: "fieldset",
                validator: function(value) {
                    return value.length > 0;
                }
            },
            inline: {
                type: Boolean,
                default: false
            }
        },
        mounted(){
            if(this.url) {
                this.getSchema();
            } else {
                if(this.schema.type === "doc") {
                    this.data = this.schema;
                } else {
                    //console.error('Root element needs to be of doc type');
                }

            }
        },
        data () {
            return {
                modelData: this.model,
                data: null,
                formOptions: {
                    validateAfterLoad: !!this.model,
                    validateAfterChanged: true,
                    validateAsync: true
                }
            }
        },
        methods: {
            getSchema: function () {
                ApiService.call(this.url, 'GET', {})
                    .then((res) => {
                        this.setSchema(res.data);
                    }, (res) => {});

            },
            setSchema: function(data) {
                this.data = data;
            },
            onSuccess: function(res, model, e){
                this.$emit('success', res, model, e);
            },
            onCancel: function(){
                this.$emit('cancel');
            }
        }
    }
</script>


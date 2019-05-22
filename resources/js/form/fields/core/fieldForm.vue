<template>
    <div class="form-fields-holder" :id="schema.id">
        <div class="form-fields-holder" v-for="child in schema.children">
            <form-component
                    :vfg="vfg"
                    :schema="child"
                    :errors="errors"
                    :options="options"
                    :model="model"
                    @validated="onFieldValidated"
                    @filter-enter="onFilterEnter"
                    @model-updated="onModelUpdated"></form-component>

        </div>

        <div class="form-buttons-holder" v-for="button in schema.buttons">
            <button
                    :disabled="model.submitting"
                    v-on:click="handleButtonClick(button)"
                    type="button"
                    :class="model.submitting? 'btn btn-primary k-spinner k-spinner--right k-spinner--md k-spinner--light' : 'btn btn-primary'">
                {{button.label}}
            </button>

        </div>
    </div>
</template>

<script>

    import fieldComponents from "../../utils/fieldsLoader.js";

    export default {
        name : "fieldForm",
        components: fieldComponents,
        props: {
            vfg: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: true
            },
            options: {
                type: Object,
                required: true
            },
            schema: {
                type: Object,
                required: true
            },
            errors: {
                type: Array,
                required: true
            }
        },
        mounted(){},
        methods: {
            onFieldValidated(res, errors, field) {
                this.$emit("validated", res, errors, field);
            },
            onModelUpdated(newVal, schema) {
                this.$emit("model-updated", newVal, schema);
            },
            handleButtonClick(btn){
                this.$emit("button-clicked", btn);
            },
            onFilterEnter(){
                for (let index in this.schema.buttons){
                    if(this.schema.buttons.hasOwnProperty(index)){
                        let button = this.schema.buttons[index];
                        if(button.type === 'submit'){
                            this.$emit("button-clicked", button);
                        }
                    }

                }
            }
        }
    };
</script>

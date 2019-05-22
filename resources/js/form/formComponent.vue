<template>
    <component ref="child"
               v-if='fieldVisible(schema)'
               :is="getFieldType(schema)"
               :vfg="vfg"
               :field="schema"
               :model="model"
               :schema="schema"
               :options="options"
               :errors="errors"
               @validated="onFieldValidated"
               @model-updated="onModelUpdated"
               @filter-enter="onFilterEnter"
               @button-clicked="onButtonClicked"></component>
</template>
<script>

    import {isNil, isFunction } from "lodash";
    import formMixin from "./formMixin.js";
    import conditional from './utils/conditional';
    import fieldComponents from "./utils/fieldsLoader.js";

    export default {
        name: "formComponent",
        components: fieldComponents,
        mixins: [formMixin, conditional],
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
        mounted(){

        },
        methods: {
            // Get type of field. It'll be the name of HTML element
            getFieldType(fieldSchema) {

                let wrappers = ['section',
                                'form',
                                'row',
                                'collection',
                                'column',
                                'tag'];

                if(wrappers.includes(fieldSchema.type)){
                    return `field-${fieldSchema.type}`;
                }else{
                    return 'form-group';
                }

            },
            onFieldValidated(res, errors, field) {
                this.$emit("validated", res, errors, field);
            },
            onModelUpdated(newVal, schema) {
                this.$emit("model-updated", newVal, schema);
            },
            onButtonClicked(btn){
                this.$emit("button-clicked", btn);
            },
            onFilterEnter(btn){
                this.$emit("filter-enter", btn);
            }
        }
    };
</script>

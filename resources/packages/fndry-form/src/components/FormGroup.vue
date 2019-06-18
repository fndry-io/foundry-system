<template>
    <ValidationProvider ref="provider" :rules="schema.rules" :name="name" v-slot="{ validate, errors, valid, failedRules }" :slim="true">
        <b-form-group
                :id="`fieldset-${schema.name}`"
                :description="schema.help"
                :label="schema.label"
                :label-for="schema.id"
                :state="valid"
        >
            <div class="field-wrap">
                <component ref="child"
                           :is="fieldType(schema)"
                           :schema="schema"
                           :id="schema.id"
                           :name="name"
                           v-model="value"
                           @input="onInput"
                           @change="onChange"
                           :state="valid"
                           :validation="{validate}"
                ></component>
            </div>
            <b-form-invalid-feedback :state="valid">
                <span v-for="(error, index) in errors" :key="index">{{error}}</span>
            </b-form-invalid-feedback>
        </b-form-group>
    </ValidationProvider>
</template>

<script>

    import {get as objGet, merge as objMerge} from "lodash";
    import { BFormGroup, BFormInvalidFeedback } from 'bootstrap-vue'
    import { ValidationProvider } from 'vee-validate';

    import {fields} from "../types/index.js";
    import field from "../mixins/field.js";
    import conditional from "../mixins/conditional";

    export default {
        name: "form-group",
        components: objMerge({BFormGroup, BFormInvalidFeedback, ValidationProvider}, fields),
        mixins: [
            field,
            conditional
        ],
        props: {
            schema: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: false
            },
            errors: {
                type: Object,
                required: false
            }
        },
        data() {
            return {
                value: this.fieldValue(this.schema),
                name: this.fieldName(this.schema)
            };
        },
        mounted() {
            if (this.errors) {
                this.applyErrors(this.errors);
            }
        },
        watch: {
            errors(newValue, oldValue){
                this.applyErrors(newValue);
            }
        },
        methods: {
            fieldValue(field) {
                return objGet(this.model, `${field.name}`);
            },
            onInput(value) {
                this.$emit('input', this.schema.name, value);
            },
            onChange(value) {
                this.$emit('change', this.schema.name, value);
            },
            onBlur() {
                this.$emit('blur', this.schema.name);
            },
            onFocus() {
                this.$emit('focus', this.schema.name);
            },
            applyErrors(errors){
                const _errors = objGet(errors, this.name, []);
                if (_errors.length > 0) {
                    this.$refs.provider.applyResult({
                        errors: _errors, // array of string errors
                        valid: false, // boolean state
                        failedRules: {} // should be empty since this is a manual error.
                    });
                }
            }
        }
    };
</script>

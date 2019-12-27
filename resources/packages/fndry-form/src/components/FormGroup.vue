<template>
    <ValidationProvider ref="provider" :vid="getVid()" :rules="schema.rules" :name="name" v-slot="{ validate, errors, valid, failedRules }" :slim="true">
        <b-form-group
                :id="`fieldset-${schema.name}`"
                :description="schema.help"
                :label="(!noLabel) ? schema.label : null"
                :label-for="schema.id"
                :state="valid"
                :required="fieldRequired(schema)"
                :class="{'required': fieldRequired(schema)}"
                v-if="schema.type !== 'hidden'"
                :label-cols="(schema.inline) ? 2: null"
        >
            <div class="field-wrap">
                <component ref="name"
                           :is="fieldType(schema)"
                           :schema="schema"
                           :id="schema.id"
                           :name="name"
                           v-model="value"
                           :root-model="model"
                           @input="onInput"
                           @change="onChange"
                           :state="valid"
                           :validation="{validate}"
                           :required="fieldRequired(schema)"
                           :disabled="schema.disabled"
                ></component>
            </div>
            <b-form-invalid-feedback :state="valid">
                <span v-for="(error, index) in errors" :key="index">{{error}}</span>
            </b-form-invalid-feedback>
        </b-form-group>
        <fndry-field-hidden
                v-else
                ref="name"
                :id="schema.id"
                :name="name"
                v-model="value"
                :root-model="model"
                @input="onInput"
                @change="onChange"
        ></fndry-field-hidden>
    </ValidationProvider>
</template>

<script>

    import {get as objGet, merge as objMerge, each} from "lodash";
    import { BFormGroup, BFormInvalidFeedback } from 'bootstrap-vue'
    import { ValidationProvider } from 'vee-validate';

    import fields from "../types/fields";
    import field from "../mixins/field";
    import schema from "../mixins/schema";

    export default {
        name: "form-group",
        components: objMerge({BFormGroup, BFormInvalidFeedback, ValidationProvider}, fields),
        mixins: [
            field,
            schema
        ],
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
            model: function(newValue, oldValue){
                let _newVal = objGet(newValue, `${this.schema.name}`);
                let _oldVal = objGet(oldValue, `${this.schema.name}`);
                if (_newVal !== _oldVal) {
                    if (_newVal === null) {
                        this.$refs['provider'].reset();
                    }
                    this.value = _newVal;
                }
            },
            errors: function(newValue, oldValue){
                this.applyErrors(newValue);
            }
        },
        methods: {
            fieldValue(field) {
                return objGet(this.model, `${field.name}`);
            },
            onInput(value) {
                this.value = value;
                this.$emit('input', this.schema.name, value);
            },
            onChange(value) {
                this.value = value;
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
            },
            getVid() {
                if (this.schema.name.indexOf('_confirmation') !== false) {
                    return this.schema.name;
                }
                return undefined;
            }
        }
    };
</script>


<style>
    .form-group.required > label::after {
        display: inline;
        content: ' *';
        color: #dc3545;
    }
</style>

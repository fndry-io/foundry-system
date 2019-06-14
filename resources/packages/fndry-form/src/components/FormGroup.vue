<template>
    <form-field :schema="schema" :model="model">
        <div :class="['form-group', inputClassName]">
            <label :for="schema.id">
                {{schema.label}}
            </label>
            <div class="field-wrap">
                <component ref="child"
                           :is="fieldType(schema)"
                           :schema="schema"
                           :id="schema.id"
                           :name="name"
                           v-model="value"
                           @change="onChange"
                           @input="onInput"
                ></component>
            </div>
            <!--<div v-if="fieldErrors(field).length > 0" class="invalid-feedback errors">-->
            <!--<span v-for="(error, index) in fieldErrors(field)" :key="index" v-html="error"></span>-->
            <!--</div>-->
            <span class="form-text text-muted" v-html='schema.help'>{{schema.help}}</span>
        </div>
    </form-field>
</template>
<script>

    import {get} from "lodash";
    import field from "../mixins/field.js";
    import {fields} from "../fields/index.js";
    import {config} from '../config';
    import {fndryFormState, fndryFormParentForm} from '../utils/providers';
    import {hyphenate, getClasses} from '../utils';

    export default {
        name: "form-group",
        components: fields,
        mixins: [
            field
        ],
        props: {
            schema: {
                type: Object,
                required: true
            },
            model: {
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
        inject: {
            fndryFormState,
            fndryFormParentForm
        },
        methods: {
            getClasses(classConfig) {
                let s = get(this.fndryFormState, this.name);
                if (s) {
                    return Object.keys(s.$error).reduce((classes, error) => {
                        classes[classConfig.invalid + '-' + hyphenate(error)] = true;
                        return classes;
                    }, getClasses(classConfig, s));
                }
            },
            onInput($event) {
                this.$emit('input', this.schema.name, $event.target.value);
            },
            onChange($event) {
                this.$emit('change', this.schema.name, $event.target.value);
            },
            onBlur() {
                this.$emit('blur', this.schema.name);
            },
            onFocus() {
                this.$emit('focus', this.schema.name);
            },
            fieldValue(schema) {
                return get(this.model, `${schema.name}`);
            }
        },
        computed: {
            className() {
                return this.getClasses(config.validateClasses);
            },
            inputClassName() {
                return this.getClasses(config.inputClasses);
            }
        }
    };
</script>

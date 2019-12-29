import {get as objGet, merge as objMerge, each} from "lodash";
import { ValidationProvider } from 'vee-validate';

export default {
    data() {
        return {
            value: this.fieldValue(this.schema),
            name: this.fieldName(this.schema)
        };
    },
    components: {
        ValidationProvider
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

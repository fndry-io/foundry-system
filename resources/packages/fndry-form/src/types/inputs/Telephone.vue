<template>
    <div>
        <b-form-input :type="this.schema.type"
                      :id="id"
                      :name="name"
                      :value="model"
                      :placeholder="schema.placeholder"
                      :disabled="disabled"
                      :autocomplete="autocomplete"
                      :maxLength="schema.max"
                      :required="schema.required"
                      :state="state"
                      :step="schema.step"
                      @input="onInput"
                      @change="onChange"
                      @blur="$emit('blur')"
        ></b-form-input>
    </div>
</template>

<script>

    import abstractInput from '../abstractInput';
    import { AsYouType } from 'libphonenumber-js'

    export default {
        name: "fndry-field-input",
        mixins: [
            abstractInput
        ],
        data() {
            return {
                model: new AsYouType().input(this.value)
            }
        },
        methods: {
            onInput(value){
                if (value && value[0] !== '+') {
                    value = '+' + this.schema.country + value;
                }
                this.model = this.formatNumber(value);
                this.$emit('input', this.cleanNumber(this.model));
            },
            onChange(){
                this.$emit('change', this.cleanNumber(this.model));
            },
            cleanNumber(value){
                return value.replace(/[^0-9\+]/gi, '');
            },
            formatNumber(value){
                return new AsYouType().input(value);
            }
        }

    };
</script>

<template>
    <div>
        <b-form-input type="text"
                      :id="id"
                      :name="name"
                      :value="model"
                      :placeholder="schema.placeholder"
                      :disabled="disabled"
                      :autocomplete="autocomplete"
                      :maxLength="maxLength"
                      :required="schema.required"
                      :state="state"
                      @input="onInput"
                      @change="onChange"
                      @blur="$emit('blur')"
        ></b-form-input>
    </div>
</template>

<script>

    import abstractInput from '../abstractInput';
    import numeral from 'numeral';

    export default {
        name: "fndry-field-input",
        mixins: [
            abstractInput
        ],
        data() {
            return {
                model: null,
                format: `${(this.schema.symbol) ? this.schema.symbol : '$'} 0,0[.]0[0]`
            }
        },
        created() {
            if (this.value !== null) {
                this.model = this.formatNumber(this.value);
            }
        },
        methods: {
            onInput(value){
                let val = this.cleanNumber(value);
                this.model = this.formatNumber(val);
                this.$emit('input', val);
            },
            onChange(){
                this.$emit('change', this.cleanNumber(this.model));
            },
            cleanNumber(value){
                return value.replace(/[^0-9\.]/gi, '');
            },
            formatNumber(value){
                if (value > 0) {
                    return numeral(value).format(this.format);
                } else {
                    return value;
                }

            }
        },
        computed: {
            maxLength: function() {
                return this.schema.max.toString().length + 5;
            }
        }

    };
</script>

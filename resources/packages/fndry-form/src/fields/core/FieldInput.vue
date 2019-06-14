<template>
    <div>
        <input :type="this.schema.type"
               :id="id"
               :name="name"
               v-model="model"
               :placeholder="this.schema.placeholder"
               class="form-control"
               v-on="$listeners"
               @focus="onFocus"
               @blur="onBlur"
               :min="schema.min"
               :max="schema.max"
               :minlength="schema.min"
               :maxlength="schema.max"
        >
    </div>
</template>

<script>
    import {set, get} from 'lodash';
    import {slugify} from '../../utils/schema';

    export default {
        name: "field-input",
        props: {
            schema: {
                type: Object,
                required: true
            },
            id: {
                type: String
            },
            name: {
                type: String
            },
            value: {
                type: [String,Number,Boolean,Array,InputEvent],
                required: false
            }
        },
        data(){
            return {
                model: this.value
            }
        },
        mounted(){
            console.log(this.schema);
        },
        computed: {
            fieldId() {
                return slugify(this.schema.name);
            },
            fieldName() {
                return this.schema.name;
            },
        },
        methods: {
            onBlur() {
                this.$emit('blur');
            },
            onFocus() {
                this.$emit('focus');
            }
        }
    };
</script>

<template>
    <div>
        <b-form-input :type="this.schema.type"
                      :id="id"
                      :name="name"
                      v-model="model"
                      :placeholder="schema.placeholder"
                      :min="schema.min"
                      :max="schema.max"
                      :required="schema.required"
                      v-on="$listeners"
                      :state="state"
        ></b-form-input>
    </div>
</template>

<script>
    import { BFormInput } from 'bootstrap-vue'

    export default {
        name: "fndry-field-input",
        components: {
            BFormInput
        },
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
            },
            state: Boolean,
            validation: Object
        },
        data(){
            return {
                model: this.value
            }
        },
    };
</script>

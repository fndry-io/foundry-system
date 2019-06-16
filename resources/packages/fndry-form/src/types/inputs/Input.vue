<template>
    <div>
        <b-form-input :type="this.schema.type"
                      :id="id"
                      :name="name"
                      v-model="model"
                      :placeholder="schema.placeholder"
                      :disabled="disabled"
                      :autocomplete="schema.autocomplete"
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

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-input",
        components: {
            BFormInput
        },
        mixins: [
            abstractInput
        ]

    };
</script>

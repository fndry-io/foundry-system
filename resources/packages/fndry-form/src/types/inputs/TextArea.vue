<template>
    <div>
        <b-form-textarea :type="this.schema.type"
                         :id="id"
                         :name="name"
                         v-model="model"
                         :placeholder="schema.placeholder"
                         :disabled="disabled"
                         :min="schema.min"
                         :max="schema.max"
                         :required="schema.required"
                         v-on="$listeners"
                         :state="state"
                         :rows="schema.multiline"
        ></b-form-textarea>
    </div>
</template>

<script>
    import {BFormTextarea} from 'bootstrap-vue'

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-textarea",
        components: {
            BFormTextarea
        },
        mixins: [
            abstractInput
        ]

    };
</script>

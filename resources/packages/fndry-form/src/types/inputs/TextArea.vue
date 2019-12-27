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
    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-textarea",
        mixins: [
            abstractInput
        ]

    };
</script>
